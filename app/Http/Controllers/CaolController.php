<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Co_Usuario;
use App\Permissao_Sistem;
use App\Cao_Os;
use App\Cao_Fatura;
use App\Cao_Salario;
use Illuminate\Support\Facades\DB;

class CaolController extends Controller
{
    public function index()
      {
          $consultores  =  DB::table('cao_usuario')
          ->join('permissao_sistema', 'cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
          ->where('permissao_sistema.co_sistema', '=',1 )
          ->where('permissao_sistema.in_ativo','=','S')
          ->whereIn('permissao_sistema.co_tipo_usuario', array(0,1,2))
          ->select('cao_usuario.no_usuario','cao_usuario.co_usuario')
          ->orderby('no_usuario')
          ->get();
          return response($consultores, 200, ['Content-Type' => 'application/json']);
      }

    public function getRelatorio(Request $request)
         {
                   $periodos = json_decode($request['periodos']);
                   $consultores = json_decode($request['consultores']);

                   for($i=0; $i < count($consultores); $i++){
                       for($j=0; $j < count($periodos); $j++){
                           $date_explode = explode('-', $periodos[$j]->periodo_num);

                           $query = DB::table('cao_fatura')
                           ->join('cao_os', 'cao_fatura.co_os', '=', 'cao_os.co_os')
                           ->where('cao_os.co_usuario', '=', $consultores[$i]->co_usuario)
                           ->whereYear('cao_fatura.data_emissao', $date_explode[0])
                           ->whereMonth('cao_fatura.data_emissao', $date_explode[1]);

                           $receita_liquida[$i][$j] = round($query->sum('cao_fatura.valor'), 2);
                           $comissao_cn[$i][$j] = $query->sum('cao_fatura.comissao_cn');
                           $total_imp_inc[$i][$j] = $query->sum('cao_fatura.total_imp_inc');

                           $custo = DB::table('cao_salario')
                           ->where('co_usuario', '=', $consultores[$i]->co_usuario)
                           ->get('brut_salario');

                           if(count($custo) > 0)
                               $custo_fixo[$i][$j] = round($custo[0]->brut_salario, 2);
                           else
                               $custo_fixo[$i][$j] = 0;

                           $comissao[$i][$j] = round(($receita_liquida[$i][$j] - ($receita_liquida[$i][$j] * ($total_imp_inc[$i][$j]/100))) * ($comissao_cn[$i][$j]/100), 2);
                           $lucro[$i][$j] = round($receita_liquida[$i][$j] - $custo_fixo[$i][$j] - $comissao[$i][$j], 2);


                       }
                     }
                      return response()->json(['datos_relatorio' => $consultores,
                                               'receita_liquida' => $receita_liquida,
                                               'periodos'        => $periodos,
                                               'custo_fixo'      => $custo_fixo,
                                               'comissao'        => $comissao,
                                               'lucro'           => $lucro,
                                              ], 200);
         }

     public function getPizza(Request $request)
          {
                $periodos = json_decode($request['periodos']);
                $consultores = json_decode($request['consultores']);

                $position = count($periodos);

                $date_explode = explode('-', $periodos[$position - 1]->periodo_num);

                $last_day = date("d",(mktime(0,0,0,$date_explode[1]+1,1,$date_explode[0])-1));

                $datei = $periodos[0]->periodo_num.'-01';
                $datef = $periodos[$position - 1]->periodo_num.'-'.$last_day;
                $consultor['consultor']=[];
                for($i=0; $i < count($consultores); $i++){

                $query = DB::table('cao_fatura')
                ->join('cao_os', 'cao_fatura.co_os', '=', 'cao_os.co_os')
                ->where('cao_os.co_usuario', '=', $consultores[$i]->co_usuario)
                ->whereBetween('cao_fatura.data_emissao', [$datei, $datef]);

                $receita_liquida[$i] = round($query->sum('cao_fatura.valor'), 2);
                $total_imp_inc[$i] = round($query->sum('cao_fatura.total_imp_inc')/100);
                $total[$i] = $receita_liquida[$i] - $total_imp_inc[$i];

                }

                $valor_total = array_sum($total);

                for($i=0; $i < count($total); $i++){
                    if($valor_total != 0)
                        $porcentaje[$i] = ($receita_liquida[$i]*100)/$valor_total;
                    else
                        $porcentaje[$i] = 0;
                }

                for($i=0; $i < count($porcentaje); $i++){
                        $porcentaje[$i] = round($porcentaje[$i], 2);
                }

              return  ['y'  => $porcentaje];
        }

        public function getGrafico(Request $request)

           {
               $periodos = json_decode($request['periodos']);
               $consultores = json_decode($request['consultores']);

               $position = count($periodos);

               $date_explode = explode('-', $periodos[$position - 1]->periodo_num);

               $last_day = date("d",(mktime(0,0,0,$date_explode[1]+1,1,$date_explode[0])-1));

               $datei = $periodos[0]->periodo_num.'-01';
               $datef = $periodos[$position - 1]->periodo_num.'-'.$last_day;

               for($i=0; $i < count($consultores); $i++){

                       $custo[$i] = DB::table('cao_salario')
                       ->where('co_usuario', '=', $consultores[$i]->co_usuario)
                       ->first('brut_salario');

                       $query = DB::table('cao_fatura')
                       ->join('cao_os', 'cao_fatura.co_os', '=', 'cao_os.co_os')
                       ->where('cao_os.co_usuario', '=', $consultores[$i]->co_usuario)
                       ->whereBetween('cao_fatura.data_emissao', [$datei, $datef]);

                       $receita_liquida[$i] = round($query->sum('cao_fatura.valor'), 2);
                       $total_imp_inc[$i] = round($query->sum('cao_fatura.total_imp_inc')/100);

                       $total[$i] = $receita_liquida[$i] - $total_imp_inc[$i];


               }

               $suma_custos = 0;

               for($i=0; $i < count($custo); $i++){
                   if($custo[$i] != NULL)
                       $custo_fixo[$i] = $custo[$i]->brut_salario;
                   else
                       $custo_fixo[$i] = 0;
                   $suma_custos = $suma_custos + $custo_fixo[$i];
               }

               for($i=0; $i < count($consultores); $i++){
                   $promedio_custo[$i] = round($suma_custos/count($consultores), 2);
               }

               return ['y'  => $total, 'promedio' => $promedio_custo];

           }

}
