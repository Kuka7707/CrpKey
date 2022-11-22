<template>
    <div class="container">
        <h1 v-if="!edit" class="mt-3">{{name}} 
            <button type="button" @click="edit=true" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                </svg>
            </button>
        </h1>
        <label for="intgrN" class="mt-3" v-if="edit">Переименовать интеграцию</label>
        <input v-if="edit" class="mt-3" name="intgrN" type="text" v-model="name">

        <div>Аккаунт: {{account}}</div>
        <div class="row mt-3">
            <div class="col-md-12">
                 <p v-if="!edit">Профиль: {{profile}}</p>
                
                 <p v-if="edit">Выберите профиль: </p>
                <select v-model="profile" v-if="edit">
                    <option disabled value="">Выберите один из вариантов</option>
                    <option v-for="prof in profiles_list" v-bind:value="prof.id" :key="prof.id">
                        {{ prof.name }} ({{prof.id}})
                    </option>                
                </select>
            </div>
            <div class="col-md-12 mt-3">
                <p>Периоды для отчета</p>
                от <input type="date" id="date" v-model="start_date"> до
                <input type="date" id="date" v-model="finish_date">
            </div>
        </div>
        <div class="row mt-5">
           <div class="col-md-2">
                <button type="button" class="btn btn-primary"  @click="getReport()">Получить отчет</button>
           </div>
           <div v-if="edit" class="col-md-2">
                <button type="button" class="btn btn-success"  @click="editProject()">Сохранить</button>
           </div>
        </div>
        <div class="text-center" v-if="load">
            <div class="spinner-border text-info" style="width: 5rem; height: 5rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="report mt-5" v-if="report">
            <div class="report_title">Отчет по рекламе</div>
            <div class="report_date">Данные сформированы за период {{start_date}} - {{finish_date}}</div>

            <div class="report_main">
                <div class="report_title">Яндекс Метрика. Трафик. Основные показатели</div>
                <div class="report_main_indicators">
                    <div class="indictr card">
                        <div class="indictr_name">Визиты</div>
                        <div class="indictr_int">{{report[0]}}</div>
                    </div>
                    <div class="indictr card">
                        <div class="indictr_name">Просмотры</div>
                        <div class="indictr_int">{{report[1]}}</div>
                    </div>
                    <div class="indictr card">
                        <div class="indictr_name">Глубина просмотра</div>
                        <div class="indictr_int">{{Math.round(report[2])}}</div>
                    </div>
                    <div class="indictr card">
                        <div class="indictr_name">Показатель отказов</div>
                        <div class="indictr_int">{{Math.round(report[3])}} %</div>
                    </div>
                    <div class="indictr card">
                        <div class="indictr_name">Время на сайте</div>
                        <div class="indictr_int">{{Math.round(report[4])}} сек.</div>
                    </div>
                    <div class="indictr card">
                        <div class="indictr_name">Новые посетители</div>
                        <div class="indictr_int">{{Math.round(report[5])}} %</div>
                    </div>
                </div>


                <div class="report_title">Яндекс Метрика. Конверсии</div>
                <Konvers :goals='goals' :goalsVisits='goalsVisits' :goalsConvs="goalsConvs"/>


                <div class="report_title">Яндекс Метрика. Источники трафика</div>
                <Traffic :traffics="traffics"/>


                <div class="report_title">Яндекс Метрика. Типы устройств</div>
                <Devices :demis="demis"/>


                <div class="report_title">Яндекс Метрика. Поведение пользователей</div>
                <Behavior :report="report"/>

            </div>
        </div>
    </div>
</template>

<script>
import Konvers from '../../diagrams/konvers.vue';
import Devices from '../../diagrams/devices.vue';
import Behavior from '../../diagrams/behavior.vue';
import Traffic from '../../diagrams/traffic.vue';

export default {
    props: [
        'slug',
    ],
    components:{Konvers, Devices, Behavior, Traffic},
    data(){
        return{
            edit:false,
            integr:[],
            account:null,
            profile:null,
            profiles_list:[],
            start_date:null,
            finish_date:null,
            report:null,
            demis:null,
            name: null,
            load: true,
            errored: false,
            traffics:[],
            goals:[],
            goals_stirng:'',
            goalVisits_stirng:'',
            goalsConvs:[],
            goalsVisits:[]
        }
    },
    methods: {
        getProject(){
            axios.get('/api/integrations/'+ this.slug).then(response => {
                this.integr = response.data;
                this.name = this.integr.name
                if(this.integr.profile){
                    this.profile = this.integr.profile
                }else{
                    this.edit = true
                }
                this.getLogin()
                this.getProfiles()
            }).catch(error => {
                console.log(error)
                this.errored = true;
            }).finally(() => {
                this.load = false;
            })
        },
        editProject(){
            let params = {
                _method: 'PUT',
                name: this.name,
                profile: this.profile,
                account: this.account
            };
            axios.post('/api/integrations/' + this.integr.id, params).then(res => {
                this.integr = []
                this.edit = false
                this.getProject()
            }).catch(error => {
                console.log(error)
                this.errored = true;
            })
        },
        getLogin(){
            axios.get(`https://login.yandex.ru/info?format=json&jwt_secret=${this.integr.client_secret}&with_openid_identity=true&oauth_token=${this.integr.auth_token}`)
            .then(res =>{
                // console.log(res)
                this.account = res.data.login
            }).catch(err => {
                console.log(err)
            })
        },
        getProfiles(){
            axios.get('https://api-metrika.yandex.net/management/v1/counters',{
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
            }).then(res =>{
                this.profiles_list = res.data.counters
            }).catch(err => {
                console.log(err)
            })
        },
        getGoals(){
            this.goals = []
            this.goals_stirng = ''
            this.goalVisits_stirng = ''
            axios.get(`https://api-metrika.yandex.net/management/v1/counter/${this.profile}/goals`, {
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
            }).then(res => {
                this.goals = res.data.goals
                
                for(let i = 0; i < this.goals.length; i++){
                    if(i == 0){
                        this.goals_stirng += `ym:s:goal${this.goals[i].id}conversionRate`
                        this.goalVisits_stirng += `ym:s:goal${this.goals[i].id}visits`
                    }else{
                        this.goals_stirng += `,ym:s:goal${this.goals[i].id}conversionRate`
                        this.goalVisits_stirng += `,ym:s:goal${this.goals[i].id}visits`
                    }                    
                }
            }).catch(err => console.log(err)).finally(()=>{
                this.getGoalVisits()
                this.getConvers()
            })
        },
        getGoalVisits(){
            this.goalsVisits = []
            axios.get(`https://api-metrika.yandex.net/stat/v1/data?metrics=${this.goalVisits_stirng}&date1=${this.start_date}&date2=${this.finish_date}&ids=${this.profile}`, {
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
            }).then(res => {
                this.goalsVisits = res.data.totals                
            }).catch(err=> {
                console.log(err)
            })
        },
        getConvers(){
            this.goalsConvs = []
            axios.get(`https://api-metrika.yandex.net/stat/v1/data?metrics=${this.goals_stirng}&date1=${this.start_date}&date2=${this.finish_date}&group=day&id=${this.profile}`, {
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
            }).then(res => {
                this.goalsConvs = res.data.totals
            }).catch(err=> {
                console.log(err)
            })
        },
        getTraffic(){
            this.traffics = []
            axios.get(`https://api-metrika.yandex.net/stat/v1/data?metrics=ym:s:visits&dimensions=ym:s:lastSignTrafficSource&date1=${this.start_date}&date2=${this.finish_date}&group=day&id=${this.profile}`, {
                    headers: {
                        'Authorization': `OAuth ${this.integr.auth_token}`,
                    }
            }).then(res => {
                this.traffics = res.data.data
            }).catch(err => console.log(err))
        },
        async getReport(){
            if(!this.start_date || !this.finish_date){
                alert('Укажите дату')
            }
            else{
                this.report= null,
                this.load = true,

                await axios.get(`https://api-metrika.yandex.net/stat/v1/data?metrics=ym:s:visits,ym:s:pageviews,ym:s:pageDepth,ym:s:bounceRate,ym:s:avgVisitDurationSeconds,ym:s:newUserVisitsPercentage&dimensions=ym:s:deviceCategory&date1=${this.start_date}&date2=${this.finish_date}&ids=${this.profile}`, {
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
                }).then(res =>{
                    this.report = res.data.totals
                    this.demis = res.data.data
                }).catch(err => console.log(err))
                .finally(()=>{
                    this.load = false
                    this.getGoals()
                    this.getTraffic()
                })
            }
            
        }
    },
    mounted(){
        this.getProject()
    }
}
</script>
<style>
        .report_title{
            font-size: 22px;
            font-weight: 700;
        }
        .report_date{
            font-size: 18px;
            margin-top: 5px;
        }
        .report_main{
            margin-top: 30px;
        }
        .report_main_indicators{
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin-top: 15px;
            margin-bottom: 30px;
        }
        .indictr{
            flex: 0 1 31.333%;
            text-align: center;
            margin-bottom: 15px;
            padding: 15px 0;
        }
        .indictr_name{
            font-size: 16px;
        }
        .indictr_int{
            margin-top: 10px;
            font-size: 18px;
            font-weight: 700;
        }
        .report_waves{
            margin-bottom: 20px;
        }
        .wave{
            margin-bottom: 20px;
        }
        .report_desc{
            font-size: 14px;
            margin: 5px 0;
        }
        .report_descs{
            margin-bottom:30px;
        }
</style>