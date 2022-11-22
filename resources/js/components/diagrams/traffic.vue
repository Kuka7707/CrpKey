<template>
    <div class="report_stolb">
        <div class="report_stolb_diagram">
            <Bar
                :chart-options="chartOptions"
                :chart-data="chartData"
                :chart-id="chartId"
                :dataset-id-key="datasetIdKey"
                :plugins="plugins"
                :css-classes="cssClasses"
                :styles="styles"
                :width="width"
                :height="height"
            />
        </div>
        <div class="report_main_indicators">
            <div class="indictr card" v-for="traffic in traffics" :key="traffic.index">
                <div class="indictr_name">{{traffic.dimensions[0].name}}</div>
                <div class="indictr_int">{{traffic.metrics[0]}}</div>
            </div>                  
        </div>
    </div>
</template>

<script>
import { Bar } from 'vue-chartjs/legacy'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

export default {
    name: 'BarChart',
    components: {
        Bar
    },
    props:{
        traffics:{
            type: Array,
            required:true
        },
        chartId: {
            type: String,
            default: 'bar-chart'
        },
        datasetIdKey: {
            type: String,
            default: 'label'
        },
        width: {
            type: Number,
            default: 400
        },
        height: {
            type: Number,
            default: 400
        },
        cssClasses: {
            default: '',
            type: String
        },
        styles: {
            type: Object,
            default: () => {}
        },
        plugins: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            names: [],
            numbers:[],
            chartData: {
                labels: this.names,
                datasets: [
                    {
                        label: 'Поведение пользователей',
                        backgroundColor: '#f87979',
                        data: this.numbers
                    }
                ]
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            
        }
    },
    methods:{
        newTraffics(){
            this.names = this.traffics.map(t => t.dimensions[0].name)
            this.numbers = this.traffics.map(t => t.metrics[0])
        }
    },
    mounted(){
        this.newTraffics()
    }
}
</script>
<style>
    
</style>