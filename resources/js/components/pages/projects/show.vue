<template>
    <div class="container">
        <h1 class="mt-3">{{name}}</h1>
        <div>Аккаунт: {{account}}</div>
        <div class="row mt-3">
            <div class="col-md-4">
                 <p>Выборон профиль:</p>
                <select v-model="profile">
                    <option disabled value="">Выберите один из вариантов</option>
                    <option v-for="prof in profiles_list" v-bind:value="prof.id" :key="prof.id">
                        {{ prof.name }} ({{prof.id}})
                    </option>                
                </select>
            </div>
            <div class="col-md-4">
                <p>Периоды для отчета</p>
                от <input type="date" id="date" v-model="start_date"> до
                <input type="date" id="date" v-model="finish_date">
            </div>
        </div>
        <div class="row mt-5">
           <div class="col-md-3">
                <button type="button" class="btn btn-success"  @click="getReport()">Получить отчет</button>
           </div>
        </div>
        <div class="report mt-5" v-if="report">
            <div class="report_title">Отчет по рекламе</div>
            <div class="report_date">Данные сформированы за период {{start_date}} - {{finish_date}}</div>

            <div class="report_main">
                <div class="report_title">Яндекс Метрика. Трафик. Основные показатели</div>
                <div class="report_main_indicators">
                    <div class="indictr">
                        <div class="indictr_name">Визиты</div>
                        <div class="indictr_int">{{report.totals[0]}}</div>
                    </div>
                    <div class="indictr">
                        <div class="indictr_name">Просмотры</div>
                        <div class="indictr_int">812</div>
                    </div>
                    <div class="indictr">
                        <div class="indictr_name">Глубина просмотра</div>
                        <div class="indictr_int">812</div>
                    </div>
                    <div class="indictr">
                        <div class="indictr_name">Показатель отказов</div>
                        <div class="indictr_int">812</div>
                    </div>
                    <div class="indictr">
                        <div class="indictr_name">Время на сайте</div>
                        <div class="indictr_int">812</div>
                    </div>
                    <div class="indictr">
                        <div class="indictr_name">Новые посетители</div>
                        <div class="indictr_int">812</div>
                    </div>
                </div>
                <div class="report_title">Яндекс Метрика. Конверсии</div>
                <div class="report_waves">
                    <div class="wave">
                        <div class="wave_title">Клик по номеру телефона</div>
                        <div class="wave_diagramm">

                        </div>
                    </div>
                    <div class="wave">
                        <div class="wave_title">Клик по кнопке whatsapp</div>
                        <div class="wave_diagramm">
                            
                        </div>
                    </div>
                    <div class="wave">
                        <div class="wave_title">Отправка формы</div>
                        <div class="wave_diagramm">
                            
                        </div>
                    </div>
                </div>
                <div class="report_descs">
                    <p class="report_desc">Целевые визиты - Количество визитов, достигших цели.</p>
                    <p class="report_desc">Конверсия - Доля целевых визитов в общемчисле визитов.</p>
                    <p class="report_desc">CPA - Стоимость одного целевого визита. Как считается: Расходы / Целевые визиты.</p>
                </div>
                <div class="indictr_name">Целевые визиты</div>
                <div class="report_main_indicators">
                    <div class="indictr">
                        <div class="indictr_name">Клик по номеру телефона</div>
                        <div class="indictr_int">812</div>
                    </div>
                    <div class="indictr">
                        <div class="indictr_name">Клик по кнопке whatsapp </div>
                        <div class="indictr_int">812</div>
                    </div>
                    <div class="indictr">
                        <div class="indictr_name">Отправка формы</div>
                        <div class="indictr_int">812</div>
                    </div>
                </div>
                <div class="indictr_name">Конверсии, %</div>
                <div class="report_main_indicators">
                    <div class="indictr">
                        <div class="indictr_name">Клик по номеру телефона</div>
                        <div class="indictr_int">812</div>
                    </div>
                    <div class="indictr">
                        <div class="indictr_name">Клик по кнопке whatsapp </div>
                        <div class="indictr_int">812</div>
                    </div>
                    <div class="indictr">
                        <div class="indictr_name">Отправка формы</div>
                        <div class="indictr_int">812</div>
                    </div>
                </div>
                <div class="report_title">Яндекс Метрика. Источники трафика</div>
                <div class="report_stolb">
                    <div class="report_stolb_diagram">Diagramma</div>
                    <div class="report_main_indicators">
                        <div class="indictr">
                            <div class="indictr_name">Facebook (Переходыпорекламе)</div>
                            <div class="indictr_int">812</div>
                        </div>
                        <div class="indictr">
                            <div class="indictr_name">Google Ads (Переходыпорекламе)</div>
                            <div class="indictr_int">812</div>
                        </div>
                        <div class="indictr">
                            <div class="indictr_name">crp.worksection.com (Переходыпоссылкам на сайтах)</div>
                            <div class="indictr_int">812</div>
                        </div>
                        <div class="indictr">
                            <div class="indictr_name">Google (Переходыизпоисковых систем)</div>
                            <div class="indictr_int">812</div>
                        </div>
                        <div class="indictr">
                            <div class="indictr_name">link.2gis.ru (Переходыпоссылкам на сайтах)</div>
                            <div class="indictr_int">812</div>
                        </div>
                        <div class="indictr">
                            <div class="indictr_name">l.wl.co</div>
                            <div class="indictr_int">812</div>
                        </div>                    
                    </div>
                </div>
                <div class="report_title">Яндекс Метрика. Типы устройств</div>
                <div class="report_pipe">
                    <div class="report_pipe_diagram">Diagramma</div>
                    <div class="report_main_indicators">
                        <div class="indictr">
                            <div class="indictr_name">Смартфоны</div>
                            <div class="indictr_int">812</div>
                        </div>
                        <div class="indictr">
                            <div class="indictr_name">ПК</div>
                            <div class="indictr_int">812</div>
                        </div>
                        <div class="indictr">
                            <div class="indictr_name">Планшеты</div>
                            <div class="indictr_int">812</div>
                        </div>                   
                    </div>
                </div>
                <div class="report_title">Яндекс Метрика. Поведение пользователей</div>
                <div class="report_big_stolb">
                    <div class="report_big_stolb_diagramm">diagramma</div>
                    <div class="report_main_indicators">
                        <div class="indictr">
                            <div class="indictr_name">Глубина просмотров</div>
                            <div class="indictr_int">812</div>
                        </div>
                        <div class="indictr">
                            <div class="indictr_name">Время на сайте </div>
                            <div class="indictr_int">812</div>
                        </div>
                        <div class="indictr">
                            <div class="indictr_name">Показатель отказов</div>
                            <div class="indictr_int">812</div>
                        </div>      
                        <div class="indictr">
                            <div class="indictr_name">Новые посетители, %</div>
                            <div class="indictr_int">812</div>
                        </div>               
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'slug',
    ],
    data(){
        return{
            integr:[],
            account:null,
            profile:null,
            profiles_list:[],
            start_date:null,
            finish_date:null,
            report:null,
            name: null,
            load: true,
            errored: false
        }
    },
    methods: {
        getProject(){
            axios.get('/api/integrations/'+ this.slug).then(response => {
                this.integr = response.data;
                this.name = this.integr.name
                this.profile = this.integr.profile
                console.log(this.profile)
                this.getLogin()
                this.getProfiles()
            }).catch(error => {
                console.log(error)
                this.errored = true;
            }).finally(() => {
                this.load = false;
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
        getReport(){
            if(!this.start_date || !this.finish_date){
                alert('Укажите дату')
            }
            else{
                axios.get(`https://api-metrika.yandex.net/stat/v1/data?ids=${this.profile}&metrics=ym:s:visits&date1=${this.start_date}&date2=${this.finish_date}`, {
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
                }).then(res =>{
                    console.log(res.data)
                    this.report = res.data
                }).catch(err => console.log(err))
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
            justify-content: space-between;
            margin-top: 15px;
            margin-bottom: 30px;
        }
        .indictr{
            flex: 0 1 33.333%;
            text-align: center;
            margin-bottom: 15px;
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