<template>
    <div>
        <div class="report_stolb mt-4">
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
        report:{
            type: Array,
            required:true,
            default:[]
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
            default: 600
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
                labels: ['Глубина просмотров', 'Время на сайте', 'Показатель отказов', 'Новые посетители, %' ],
                datasets: [
                    {
                        label: 'Поведение пользователей',
                        backgroundColor: '#f87979',
                        data: [Math.round(this.report[2]), Math.round(this.report[4]), Math.round(this.report[3]), Math.round(this.report[5])]
                    }
                ]
            },
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            
        }
    }
}
</script>
<style>
    
</style>