<template>
<v-content>
<v-container>
<v-layout row wrap>
     <template>
        <v-col cols="2">
          <v-toolbar color="indigo" dark height="40%">
          <v-toolbar-title >Desde</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon>
           <v-icon>mdi-calendar-clock</v-icon>
          </v-btn>
          </v-toolbar>
          <v-menu
              :close-on-content-click="true"
              :nudge-right="40"
              lazy
              transition="scale-transition"
              offset-y
              full-width
              min-width="290px"
              >
              <template v-slot:activator="{ on }">
                  <v-text-field
                  v-model="date1"
                  readonly
                  v-on="on"
                  width="80px"
                  ></v-text-field>
              </template>
              <v-date-picker v-model="date1" type="month" locale="es-cl"></v-date-picker>
          </v-menu>
      </v-col>
      <v-col cols="2" >
        <v-toolbar color="indigo" dark height="40%">
        <v-toolbar-title >Hasta</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon>
         <v-icon>mdi-calendar-clock</v-icon>
        </v-btn>
        </v-toolbar>
        <v-menu
            :close-on-content-click="true"
            :nudge-right="40"
            lazy
            transition="scale-transition"
            offset-y
            full-width
            min-width="290px"
            >
            <template v-slot:activator="{ on }">
                <v-text-field
                v-model="date2"
                readonly
                v-on="on"
                width="80px"
                ></v-text-field>
            </template>
            <v-date-picker v-model="date2" type="month" locale="es-cl"></v-date-picker>
        </v-menu>
    </v-col>
  </template>
    <v-col cols="4">
        <v-toolbar color="indigo" dark height="40%">
        <v-toolbar-title >Consultores ({{ consultores.length }}) </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn icon>
         <v-icon>mdi-account-box-multiple</v-icon>
        </v-btn>
        </v-toolbar>
         <v-list style="max-height: 250px" class="scroll-y">
          <v-list-tile
            v-for="(item, index) in consultores"
            :key="item.no_usuario"
            @click="passLeft(item.co_usuario, item.no_usuario, index)"
          >
            <v-list-tile-avatar>
              <v-icon>mdi-square-small</v-icon>
            </v-list-tile-avatar>
            <v-list-tile-content>
              <v-list-tile-title v-text="item.no_usuario"></v-list-tile-title>
            </v-list-tile-content>

          </v-list-tile>
        </v-list>
        <v-card height="290px">
            <v-toolbar color="indigo" dark height="40%">
              <v-toolbar-title>Seleccionados ({{ seleccionados.length }})</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn icon>
              <v-icon>mdi-account-box-multiple</v-icon>
              </v-btn>
            </v-toolbar>
            <v-list style="max-height: 250px" class="scroll-y">
            <v-list-tile
               v-for="(item, index) in seleccionados"
               :key="item.no_usuario"
                @click="passRight(item.co_usuario, item.no_usuario, index)"
            >
            <v-list-tile-avatar>
            <v-icon>mdi-square-small</v-icon>
            </v-list-tile-avatar>
            <v-list-tile-content>
                <v-list-tile-title v-text="item.no_usuario"></v-list-tile-title>
            </v-list-tile-content>
            </v-list-tile>
          </v-list>
        </v-card>
    </v-col>
    <v-col cols="4">
        <v-card-actions>
        <v-btn color="info" dark @click="getRelatorio()">
            Relatorio
          <v-icon right>mdi-clipboard-check-outline</v-icon>
        </v-btn>
        <v-btn color="info" dark @click="getGrafico()">
            Gráfico
          <v-icon right>mdi-chart-areaspline</v-icon>
        </v-btn>
        <v-btn color="info" dark @click="getPizza()">
            Pizza
          <v-icon right>mdi-chart-pie</v-icon>
        </v-btn>
      </v-card-actions>
    </v-col>
    <template>
        <v-col cols="12" row v-for="(item, index) in relatorio_total" :key="index">
               <v-card v-if="table">
                   <v-card-title>
                     <div>
                         <h3 class="subheading "><v-icon small left>mdi-account</v-icon>{{ item.no_usuario }}</h3>
                     </div>
                   </v-card-title>
                   <table class="table table-hover table-striped table-lg table-bordered table-response">
                     <thead>
                       <tr>
                         <th width="20%">Periodo</th>
                         <th width="20%">Receita Líquida</th>
                         <th width="20%">Custo Fixo</th>
                         <th width="20%">Comissao</th>
                         <th width="20%">Lucro</th>
                       </tr>
                     </thead>
                     <tbody>
                     <tr v-for="(itemMes, i) in periodos" :key="i">
                       <td >{{ itemMes.periodo_num }}</td>
                       <td align="right" > R$ {{ receita_liquida[index][i] }}</td>
                       <td align="right" > R$ {{ custo_fixo[index][i] }}</td>
                       <td align="right" > R$ {{ comissao[index][i] }}</td>
                       <td align="right" > R$ {{ lucro[index][i] }}</td>
                     </tr>
                     <tr>
                       <td align="left">Totales</td>
                       <td align="right">R$ {{   suma(receita_liquida[index]) }}</td>
                       <td align="right">R$ {{   suma(custo_fixo[index]) }}</td>
                       <td align="right">R$ {{   suma(comissao[index]) }}</td>
                       <td align="right">R$ {{   suma(lucro[index]) }}</td>
                     </tr>
                    </tbody>
                   </table>
               </v-card>
        </v-col>
    </template>
    <v-col cols="12" v-if="pizza">
          <highcharts :options="chartOptions_pizza" ></highcharts>
    </v-col>
    <v-col cols="12" v-if="grafico">
          <highcharts :options="chartOptions_relatorio" ></highcharts>
    </v-col>
</v-layout>
</v-container>
</v-content>
</template>
<script>
import Highcharts from 'highcharts'
import moment from 'moment'
import 'moment/locale/es'
moment.locale('es')

    export default {
      created: function() {
          this.getConsultores();
	    },
	    data: function () {
        return {
    		   consultores: [],
           seleccionados: [],
           meses:[],
           pizza:false,
           table:false,
           grafico: false,
           menu1:false,
           menu2:false,
           relatorio_total: [],
           receita_liquida: [],
           periodos: [],
           custo_fixo: [],
           comissao: [],
           lucro: [],
           date1:'',
           date2:'',
           date: new Date().toISOString().substr(0, 10),

           hcInstance: Highcharts,
           chartOptions_pizza: {
              chart: {
                type: 'pie',
              },
              title: {
                text: 'Gráfico Pizza '
              },
              tooltip: {
                          pointFormat: '{series.name}: <b>{point.y} %</b>',
                          valueDecimals: 2
                      },
                      plotOptions: {
                          pie: {
                              allowPointSelect: true,
                              cursor: 'pointer',
                              dataLabels: {
                                  enabled: true,
                                  format: '<b>{point.name}</b>: {point.y} %',
                                  style: {
                                      color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                  }
                              },
                              showInLegend: true
                          }
             },
             series: [{ data: [] }]
           },
           chartOptions_relatorio: {
             chart: {
                     type: 'column'
                 },
                 title: {
                     text: 'Desempeño de cada consultor'
                 },
                 xAxis: {
                     categories: [
                         '1000',
                         '2000',
                         '5000',
                         '6000',
                         '8000',
                         '12000',
                         '15000',
                         '20000',
                         '24000',
                         '26000',
                         '30000',
                         '32000'
                     ],
                     crosshair: true
                 },
                 yAxis: {
                     max: 32000
                 },
                 tooltip: {
                     headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                     pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                         '<td style="padding:0"><b>{point.y} </b></td></tr>',
                     footerFormat: '</table>',
                     shared: true,
                     useHTML: true
                 },
                 plotOptions: {
                     column: {
                         pointPadding: 0.2,
                         borderWidth: 0
                     }
                 },
                 series: [{ data: [] },{ data2: [] }]
           }
        }
    	},
      methods: {

        getConsultores() {
                var urlConsultores= 'getConsultores';
                axios.get(urlConsultores).then(response => {
                this.consultores = response.data
              }).catch(e => {
                 console.log(e);
              });
        },

        getRelatorio() {
          this.meses = []
          if(this.seleccionados!=0){
            if(this.date1 != '' && this.date2 != ''){
              var startDate = moment(this.date1);
              var endDate = moment(this.date2);

              endDate.add(1,'month')

              while (startDate.isBefore(endDate)) {
                this.meses.push({'periodo_num' : startDate.format("YYYY-MM")});
                startDate.add(1, 'month');
              }
              if (endDate.isBefore(startDate)) {
                  alert('La fecha final no puede ser mayor que la inicial')
              }
              else{

                    axios.post('/getRelatorio',{
                      consultores: JSON.stringify(this.seleccionados),
                      periodos: JSON.stringify(this.meses)
                    })
                    .then(response => {
                        this.relatorio_total = response.data.datos_relatorio
                        this.receita_liquida = response.data.receita_liquida
                        this.periodos = response.data.periodos
                        this.custo_fixo = response.data.custo_fixo
                        this.comissao = response.data.comissao
                        this.lucro = response.data.lucro
                    }).catch(e => {
                      console.log(e)
                    });
                    //console.log(this.receita_liquida);
                    this.pizza=false;
                    this.table=true;
                    this.grafico=false
                  }
                }else{
                  alert("Debe seleccionar un periodo de consulta")
                }

              }else
                alert('Debe seleccionar un consultor')
            },
            getPizza() {
                     this.meses = []
                     if(this.seleccionados!=0){
                       if(this.date1 != '' && this.date2 != ''){
                         var startDate = moment(this.date1);
                         var endDate = moment(this.date2);
                         endDate.add(1,'month')

                         while (startDate.isBefore(endDate)) {
                           this.meses.push({'periodo_num' : startDate.format("YYYY-MM")});
                           startDate.add(1, 'month');
                         }
                         if (endDate.isBefore(startDate)) {
                             alert('La fecha final no puede ser mayor que la inicial')
                         }
                         else{
                           const vm = this;
                           axios.post('/getPizza',{
                             consultores: JSON.stringify(this.seleccionados),
                             periodos: JSON.stringify(this.meses)
                           })
                           .then(response => {
                              vm.chartOptions_pizza.series[0].data= response.data.y
                           }).catch(e => {
                             console.log(e);
                           });
                           this.pizza=true;
                           this.table=false;
                           this.grafico=false;
                         }
                       }else{
                         alert("Debe seleccionar un periodo de consulta")
                       }

                     }else
                       alert('Debe seleccionar un consultor')
                   },
                   getGrafico() {
                            this.meses = []
                            if(this.seleccionados!=0){
                              if(this.date1 != '' && this.date2 != ''){
                                var startDate = moment(this.date1);
                                var endDate = moment(this.date2);
                                endDate.add(1,'month')

                                while (startDate.isBefore(endDate)) {
                                  this.meses.push({'periodo_num' : startDate.format("YYYY-MM")});
                                  startDate.add(1, 'month');
                                }
                                if (endDate.isBefore(startDate)) {
                                    alert('La fecha final no puede ser mayor que la inicial')
                                }
                                else{
                                  const vm = this;
                                  axios.post('/getGrafico',{
                                    consultores: JSON.stringify(this.seleccionados),
                                    periodos: JSON.stringify(this.meses)
                                  })
                                  .then(response => {
                                     vm.chartOptions_relatorio.series[0].data= response.data.y
                                     vm.chartOptions_relatorio.series[1].data2= response.data.promedio
                                  }).catch(e => {
                                    console.log(e);
                                  });
                                  this.pizza=false;
                                  this.table=false;
                                  this.grafico=true;
                                }
                              }else{
                                alert("Debe seleccionar un periodo de consulta")
                              }

                            }else
                              alert('Debe seleccionar un consultor')
                          },
                         suma(arreglo){
                           const sum = arreglo.reduce(add);

                           function add(accumulator, a) {
                               return parseFloat(accumulator) + parseFloat(a);
                           }
                           let val = (sum/1).toFixed(2).replace('.', ',')

                           return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                         },
                          passLeft(co_usuario, no_usuario, index){
                            this.consultores.splice(index, 1);
                            this.seleccionados.unshift({co_usuario: co_usuario, no_usuario: no_usuario})
                          }
             }


        }
</script>
