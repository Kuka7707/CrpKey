<template>
    <div>
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
            
            <div class="indictr_name">Целевые визиты</div>
            <div class="report_main_indicators">
                <div class="indictr card" v-for="goal in goals" :key="goal.id">
                    <div class="indictr_name">{{goal.name}}</div>
                    <div class="indictr_int">{{goalsVisits[goals.indexOf(goal)]}}</div>
                </div>
            </div>

        </div>
        
        <div class="indictr_name">Конверсии, %</div>
            <div class="report_indicators">
                <div class="indictr card" v-for="goal in goals" :key="goal.id">
                    <div class="indictr_name">{{goal.name}}</div>
                    <div class="indictr_int">{{Math.round(goalsConvs[goals.indexOf(goal)])}} %</div>
                </div>
            </div>
        <div class="report_descs">
            <p class="report_desc">Целевые визиты - Количество визитов, достигших цели.</p>
            <p class="report_desc">Конверсия - Доля целевых визитов в общемчисле визитов.</p>
            <p class="report_desc">CPA - Стоимость одного целевого визита. Как считается: Расходы / Целевые визиты.</p>
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
        goals:{
            type: Array,
            required:true,
            default:[]
        },
        goalsVisits:{
            type: Array,
            required:true,
            default:[]
        },
        goalsConvs:{
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
            default: 400
        },
        height: {
            type: Number,
            default: 650
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
                labels: this.goals.map(g => g.name),
                datasets: [
                {
                    label: 'Целевые визиты',
                    backgroundColor: '#f87979',
                    data: this.goalsVisits
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
