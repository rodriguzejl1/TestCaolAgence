<template>
<div class="home">
<v-container>
<v-layout row wrap>
     <template>
        <v-container>
          <v-row>
          <v-col cols="6">
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
        <v-col cols="6">
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
        </v-row>
    </v-container>
  </template>
      <template>
            <v-container fill-height fluid>
              <v-layout row wrap>
                <v-flex md10 xs12> <!-- Lista 1 -->
                  <v-card height="290px" tile>
                    <v-toolbar color="indigo" dark height="40%">
                      <v-toolbar-title >Consultores ({{ consultores.length }})</v-toolbar-title>
                      <v-spacer></v-spacer>
                      <v-btn icon>
                        <v-icon>mdi-account-box-multiple</v-icon>
                      </v-btn>
                    </v-toolbar>
                    <v-list flat dense expand style="max-height: 250px" class="scroll-y">
                      <v-list-item-group color="primary">
                        <v-list-item
                          v-for="(item, index) in consultores"
                          :key="item.no_usuario"
                          @click="passLeft(item.co_usuario, item.no_usuario, index)"
                        >
                          <v-list-item-icon>
                            <v-icon v-text="item.icon">mdi-square-small</v-icon>
                          </v-list-item-icon>
                          <v-list-item-content>
                            <v-list-item-title v-text="item.no_usuario"></v-list-item-title>
                          </v-list-item-content>
                        </v-list-item>
                      </v-list-item-group>
                    </v-list>
                  </v-card>
                </v-flex>
                <v-flex md2 xs12> <!-- Botones de seleccion -->
                  <template><br><br>
                    <div class="text-center" >
                      <v-btn color="indigo" dark @click="todosLeft()">
                        Todos
                            <v-icon right>mdi-arrow-right-bold-circle-outline</v-icon>
                      </v-btn>
                      <v-btn color="indigo" dark @click="todosRight()">
                            <v-icon left light>mdi-arrow-left-bold-circle-outline</v-icon>
                        Todos
                      </v-btn>
                    </div>
                  </template>
                </v-flex>
                <v-flex md10> <!-- Lista 2 -->
                  <v-card height="290px">
                    <v-toolbar color="indigo" dark height="40%">
                      <v-toolbar-title>Seleccionados ({{ seleccionados.length }})</v-toolbar-title>
                      <v-spacer></v-spacer>
                      <v-btn icon>
                        <v-icon>mdi-account-box-multiple</v-icon>
                      </v-btn>
                    </v-toolbar>
                    <v-list flat dense expand  style="max-height: 250px" class="scroll-y">
                      <v-list-item-group color="primary">
                        <v-list-item
                          v-for="(item, index) in seleccionados"
                          :key="item.no_usuario"
                          @click="passRight(item.co_usuario, item.no_usuario, index)"
                        >
                          <v-list-item-icon>
                            <v-icon v-text="item.icon">mdi-square-small</v-icon>
                          </v-list-item-icon>
                          <v-list-item-content>
                            <v-list-item-title v-text="item.no_usuario"></v-list-item-title>
                          </v-list-item-content>
                        </v-list-item>
                      </v-list-item-group>
                    </v-list>
                  </v-card>
                </v-flex>
              </v-layout>
            </v-container>
        </template>
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
    <template>
        <v-col cols="12" row v-for="(item, index) in relatorio_total" :key="index">
               <v-card v-show="showtable">
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
    <v-col cols="12" >
      <Pizza v-show="showPie" :consultores_seleccionados="seleccionados" ref="pizza"></Pizza>
      <Grafico v-show="showBar" :consultores_seleccionados="seleccionados" ref="grafico"></Grafico>
    </v-col>
</v-layout>
</v-container>
</div>
</template>
<script>
import Chart from 'chart.js'
import moment from 'moment'
import Pizza from '../components/Pizza'
import Grafico from '../components/Grafico'
import 'moment/locale/es'
moment.locale('es')

    export default {
      name: 'home',
      components: {
        Pizza,
        Grafico
       },
      created: function() {
          this.getConsultores();
	    },
	    data: function () {
        return {
    		   consultores: [],
           seleccionados: [],
           meses:[],

           showtable:false,
           showBar: '',
           showPie: '',

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
                       this.$refs.pizza.showPie(this.meses, true)
                       this.showtable = false
                       this.showBar = false
                       this.showPie = true
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
                                     this.$refs.grafico.showBar(this.meses, true)
                                     this.showtable = false
                                     this.showBar = true
                                     this.showPie = false
                                   }
                                 }else{
                                   alert("Debe seleccionar un periodo de consulta")
                                 }

                               }else
                                 alert('Debe seleccionar un consultor')
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
                              this.showtable = true
                              this.showPie = false
                              this.showBar = false
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
                          },
                          todosLeft(){
                            if(this.seleccionados.length === 0){
                              this.seleccionados = this.consultores
                              this.consultores = []
                            }
                            else{
                              this.seleccionados = [].concat(this.consultores, this.seleccionados)
                              this.consultores = []
                            }
                          },
                          todosRight(){
                            if(this.consultores.length === 0){
                              this.consultores = this.seleccionados
                              this.seleccionados = []
                            }
                            else{
                              this.consultores = [].concat(this.seleccionados, this.consultores)
                              this.eleccionados = []
                            }
                      }
             }
        }
</script>
