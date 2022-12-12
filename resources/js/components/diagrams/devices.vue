<template>
    <div>
        <div class="report_stolb">
            <div class="report_stolb_diagram">
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
                labels: this.demis.map(d => {
                    if(d.dimensions[0].name == 'Smartphones'){
                        return 'Смартфоны'
                    }
                    else if(d.dimensions[0].name == 'PC'){
                        return 'ПК'
                    }
                    else if(d.dimensions[0].name == 'Tablets'){
                        return 'Планшет'
                    }
                    else if(d.dimensions[0].name == 'TV'){
                        return 'ТВ'
                    }else{
                        return d.dimensions[0].name
                    }

                }),
                datasets: [
                {
                    backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
                    data: this.demis.map(d => d.metrics[0]),
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