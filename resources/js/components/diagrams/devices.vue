<template>
    <div>
        <div class="report_pipe">
            <div class="report_pipe_diagram">
                <Pie
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
                <div class="indictr card" v-for="dem in demis" :key="dem.dimensions[0].id">
                    <div class="indictr_name">{{dem.dimensions[0].name }}</div>
                    <div class="indictr_int">{{dem.metrics[0][0]}}</div>
                </div>                                           
            </div>
        </div>
    </div>
</template>

<script>
import { Pie } from 'vue-chartjs/legacy'

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  CategoryScale
} from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale)

export default {
    name: 'PieChart',
    components: {
        Pie
    },
    props:{
        demis:{
            type: Array,
            required:true,
            default:[]
        },
        chartId: {
            type: String,
            default: 'pie-chart'
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
            chartData: {
                labels: this.demis.map(d => d.dimensions[0].name),
                datasets: [
                {
                    backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
                    data: this.demis.map(d => d.metrics[0][0]),
                }
                ]
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            }
        }
    }
}
</script>
<style>
    
</style>