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
                <div v-if="traffic.dimensions[0].name == 'Other ad: identified by tags'" class="indictr_name">Другая реклама</div>
                <div v-else class="indictr_name">{{traffic.dimensions[0].name}}</div>

                <div v-if="traffic.dimensions[0].url" class="indictr_desc">Переходы по ссылкам на сайтах</div>
                <div v-else-if="traffic.dimensions[0].name == 'Google' || traffic.dimensions[0].name == 'Yandex' || traffic.dimensions[0].name == 'Baidu' || traffic.dimensions[0].name == 'Bing' || traffic.dimensions[0].name == 'Ukr.net'" class="indictr_desc">Переходы из поисковых систем</div>
                <div v-else-if="traffic.dimensions[0].name == 'instagram.com' || traffic.dimensions[0].name == 'Facebook' || traffic.dimensions[0].name == 'VKontakte' || traffic.dimensions[0].name == 'Telegram'" class="indictr_desc">Переходы из социальных сетей</div>
                <div v-else-if="traffic.dimensions[0].name == 'Google Ads'" class="indictr_desc">Переходы по рекламе</div>
                <div v-else class="indictr_desc">Переходы по рекламе</div>


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
            chartData: {
                labels: this.traffics.map(t => {
                    if(t.dimensions[0].name == "Other ad: identified by tags"){
                        return 'Другая реклама'
                    }
                    else{
                        return t.dimensions[0].name
                    }
                }),
                datasets: [
                    {
                        label: 'Источники трафика',
                        backgroundColor: '#f87979',
                        data: this.traffics.map(t => t.metrics[0])
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