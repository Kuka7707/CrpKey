<template>
    <div class="container">
        <h1 v-if="!edit" class="mt-3">{{ name }}
            <button type="button" @click="edit = true" class="btn btn-outline-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil"
                    viewBox="0 0 16 16">
                    <path
                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z" />
                </svg>
            </button>
        </h1>
        <label for="intgrN" class="mt-3" v-if="edit">Переименовать интеграцию</label>
        <input v-if="edit" class="mt-3" name="intgrN" type="text" v-model="name">

        <div>Аккаунт: {{ account }}</div>
        <div class="row mt-3">
            <div class="col-md-12">
                <p v-if="!edit">Профиль: {{ profileName }}</p>

                <p v-if="edit">Выберите профиль: </p>
                <select v-model="profile" v-if="edit">
                    <option disabled value="">Выберите один из вариантов</option>
                    <option v-for="prof in profiles_list" v-bind:value="prof.id" :key="prof.id">
                        {{ prof.name }} ({{ prof.id }})
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
                <button type="button" class="btn btn-primary" @click="getReport()">Получить отчет</button>
            </div>
            <div v-if="edit" class="col-md-2">
                <button type="button" class="btn btn-success" @click="editProject()">Сохранить</button>
            </div>
            <div v-if="report" class="col-md-2">
                <button type="button" class="btn btn-success" @click="downloadPdf()">Скачать в PDF</button>
            </div>
            <div v-if="report" class="col-md-2">
                <button type="button" class="btn btn-success" @click="downloadDoc()">Скачать в DOC</button>
            </div>
        </div>
        <div class="text-center" v-if="load">
            <div class="spinner-border text-info" style="width: 5rem; height: 5rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="report mt-5" v-if="report">
            <div>
                <img ref="header" src="/logo.svg" alt="">
                <div ref="header_title" class="report_title mt-3">Отчет по рекламе {{profileName}}</div>
                <div ref="header_text" class="report_date">Данные сформированы за период {{ start_date }} - {{ finish_date }}</div>
            </div>

            <div class="report_main">
                <div ref="konvers">
                    <div class="report_title">Яндекс Метрика. Трафик. Основные показатели</div>
                    <div class="report_indicators">
                        <div class="indictr card">
                            <div class="indictr_name">Визиты</div>
                            <div class="indictr_int">{{ report[0] }}</div>
                        </div>
                        <div class="indictr card">
                            <div class="indictr_name">Просмотры</div>
                            <div class="indictr_int">{{ report[1] }}</div>
                        </div>
                        <div class="indictr card">
                            <div class="indictr_name">Глубина просмотра</div>
                            <div class="indictr_int">{{ Math.round(report[2]) }}</div>
                        </div>
                        <div class="indictr card">
                            <div class="indictr_name">Показатель отказов</div>
                            <div class="indictr_int">{{ Math.round(report[3]) }} %</div>
                        </div>
                        <div class="indictr card">
                            <div class="indictr_name">Время на сайте</div>
                            <div class="indictr_int">{{ Math.round(report[4]) }} сек.</div>
                        </div>
                        <div class="indictr card">
                            <div class="indictr_name">Новые посетители</div>
                            <div class="indictr_int">{{ Math.round(report[5]) }} %</div>
                        </div>
                    </div>
                    <hr>

                    <div class="report_title mt-5">Яндекс Метрика. Конверсии</div>
                    <Konvers :goals='goals' :goalsVisits='goalsVisits' :goalsConvs="goalsConvs" />
                    <hr>
                </div>

                <div ref="traffic">
                    <div class="report_title mt-5">Яндекс Метрика. Источники трафика</div>
                    <Traffic :traffics="traffics" />
                    <hr>
                </div>

                <div ref="device">
                    <div class="report_title mt-5">Яндекс Метрика. Типы устройств</div>
                    <Devices :demis="demis" />
                    <hr>
                </div>

                <div ref="behavior">
                    <div class="report_title mt-5">Яндекс Метрика. Поведение пользователей</div>
                    <Behavior :report="report" />
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import jsPDF from 'jspdf';
import html2canvas from "html2canvas"
import { Document, Paragraph, Packer, TextRun, ImageRun } from "docx";
import FileSaver from 'file-saver';

import Konvers from '../../diagrams/konvers.vue';
import Devices from '../../diagrams/devices.vue';
import Behavior from '../../diagrams/behavior.vue';
import Traffic from '../../diagrams/traffic.vue';

export default {
    props: [
        'slug',
    ],
    components: { Konvers, Devices, Behavior, Traffic, Document, Packer, Paragraph, TextRun, ImageRun },
    data() {
        return {
            edit: false,
            integr: [],
            account: null,
            profile: null,
            profileName:null,
            profiles_list: [],
            start_date: null,
            finish_date: null,
            report: null,
            demis: null,
            name: null,
            load: true,
            errored: false,
            traffics: [],
            goals: [],
            goals_stirng: '',
            goalVisits_stirng: '',
            goalsConvs: [],
            goalsVisits: [],
            state: {
                name: 'San Luis Potosi',
                map: 'data:image/png;base64',
                municipalities: [
                {name:'San Luis Potosi', population: 824000}, 
                {name:'Rio Verde', population: 160000},
                {name:'Cd Valles', population: 176000},
                {name:'Matehuala', population:82726}
                ],
                tourist_attractions: [
                'Tamtoc', 'Sótano de las Golondrinas', 'Cascada de Tamul' 
                ]
            }
        }
    },
    methods: {
        getProject() {
            axios.get('/api/integrations/' + this.slug).then(response => {
                this.integr = response.data;
                this.name = this.integr.name
                if (this.integr.profile) {
                    this.profile = this.integr.profile
                } else {
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
        editProject() {
            let params = {
                _method: 'PUT',
                name: this.name,
                profile: this.profile,
                account: this.account
            };
            axios.post('/api/integrations/' + this.integr.id, params).then(res => {
                this.integr = []
                this.edit = false
                for (let n of this.profiles_list){
                    if(n.id == this.profile){
                        this.profileName = n.name
                        console.log(this.profileName)
                    }
                }
                this.getProject()
            }).catch(error => {
                console.log(error)
                this.errored = true;
            })
        },
        getLogin() {
            axios.get(`https://login.yandex.ru/info?format=json&jwt_secret=${this.integr.client_secret}&with_openid_identity=true&oauth_token=${this.integr.auth_token}`)
                .then(res => {
                    // console.log(res)
                    this.account = res.data.login
                }).catch(err => {
                    console.log(err)
                })
        },
        getProfiles() {
            axios.get('https://api-metrika.yandex.net/management/v1/counters', {
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
            }).then(res => {
                this.profiles_list = res.data.counters
                for (let n of this.profiles_list){
                    if(n.id == this.profile){
                        this.profileName = n.name
                    }
                }
            }).catch(err => {
                console.log(err)
            })
        },
        getGoals() {
            this.goals = []
            this.goals_stirng = ''
            this.goalVisits_stirng = ''
            axios.get(`https://api-metrika.yandex.net/management/v1/counter/${this.profile}/goals`, {
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
            }).then(res => {
                this.goals = res.data.goals

                for (let i = 0; i < this.goals.length; i++) {
                    if (i == 0) {
                        this.goals_stirng += `ym:s:goal${this.goals[i].id}conversionRate`
                        this.goalVisits_stirng += `ym:s:goal${this.goals[i].id}visits`
                    } else {
                        this.goals_stirng += `,ym:s:goal${this.goals[i].id}conversionRate`
                        this.goalVisits_stirng += `,ym:s:goal${this.goals[i].id}visits`
                    }
                }
            }).catch(err => console.log(err)).finally(() => {
                this.getGoalVisits()
                this.getConvers()
            })
        },
        getGoalVisits() {
            this.goalsVisits = []
            axios.get(`https://api-metrika.yandex.net/stat/v1/data?metrics=${this.goalVisits_stirng}&date1=${this.start_date}&date2=${this.finish_date}&ids=${this.profile}`, {
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
            }).then(res => {
                this.goalsVisits = res.data.totals
            }).catch(err => {
                console.log(err)
            })
        },
        getConvers() {
            this.goalsConvs = []
            axios.get(`https://api-metrika.yandex.net/stat/v1/data?metrics=${this.goals_stirng}&date1=${this.start_date}&date2=${this.finish_date}&group=day&id=${this.profile}`, {
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
            }).then(res => {
                this.goalsConvs = res.data.totals
            }).catch(err => {
                console.log(err)
            })
        },
        getTraffic() {
            this.traffics = []
            axios.get(`https://api-metrika.yandex.net/stat/v1/data?metrics=ym:s:visits&dimensions=ym:s:lastSignSourceEngine&date1=${this.start_date}&date2=${this.finish_date}&group=day&id=${this.profile}`, {
                headers: {
                    'Authorization': `OAuth ${this.integr.auth_token}`,
                }
            }).then(res => {
                this.traffics = res.data.data
            }).catch(err => console.log(err))
        },
        async downloadPdf(){
            this.load = true
            let headImg, konversImg, trafficImg, deviceImg, behaviorImg
            const doc = new jsPDF('landscape');

            var canvasHead = document.createElement('canvas');
            canvasHead.setAttribute('width', 1116);
            canvasHead.setAttribute('height', 130);

            var canvasKonvers = document.createElement('canvas');
            canvasKonvers.setAttribute('width', 1116);
            canvasKonvers.setAttribute('height', 1205);

            var canvasTraffic = document.createElement('canvas');
            canvasTraffic.setAttribute('width', 1116);
            canvasTraffic.setAttribute('height', 742);

            var canvasDevice = document.createElement('canvas');
            canvasDevice.setAttribute('width', 1116);
            canvasDevice.setAttribute('height', 450);

            var canvasBehavior = document.createElement('canvas');
            canvasBehavior.setAttribute('width', 1116);
            canvasBehavior.setAttribute('height', 500);

            await html2canvas(this.$refs.header, {head: canvasHead}).then(head => {
                headImg = head.toDataURL("image/jpeg");
                return headImg
            });
            await html2canvas(this.$refs.konvers, {konvers: canvasKonvers}).then(konvers => {
                konversImg = konvers.toDataURL("image/jpeg");
                return konversImg
            });
            await html2canvas(this.$refs.traffic, {traffic: canvasTraffic}).then(traffic => {
                trafficImg = traffic.toDataURL("image/jpeg");
                return trafficImg
            });
            await html2canvas(this.$refs.device, {device: canvasDevice}).then(device => {
                deviceImg = device.toDataURL("image/jpeg");
                return deviceImg
            });
            await html2canvas(this.$refs.behavior, {behavior: canvasBehavior}).then(behavior => {
                behaviorImg = behavior.toDataURL("image/jpeg");
                return behaviorImg
            });

            let width = doc.internal.pageSize.getWidth();
            let height = doc.internal.pageSize.getHeight();


            
            doc.addImage(headImg,'JPEG',80,20);
            doc.text(this.$refs.header_title.innerHTML, 40, 50)
            doc.text(this.$refs.header_text.innerHTML, 40, 70)
            doc.addPage()
            doc.addImage(konversImg,'JPEG',1,1,280,200);
            doc.addPage()
            doc.addImage(trafficImg,'JPEG',1,1,280,200);
            doc.addPage()
            doc.addImage(deviceImg,'JPEG',1,1,280,150);
            doc.addPage()
            doc.addImage(behaviorImg,'JPEG',1,1,280,150);
            doc.save("Отчет.pdf");
            this.load = false
        },
        async downloadDoc(){
            this.load = true
            

            const doc = new Document({
                sections: [
                {
                    properties: {},
                    children: [
                        // new ImageRun({
                        //     data: ("/logo.svg"),
                        //     transformation: {
                        //         width: 235,
                        //         height: 50,
                        //     },
                        // }),
                        new Paragraph({
                            children: [
                            new TextRun(this.$refs.header_title.innerHTML),                       
                            ],
                        }),
                        new Paragraph({
                            children: [
                            new TextRun(this.$refs.header_text.innerHTML),                       
                            ],
                        }),
                        new Paragraph({
                            children: [
                            new TextRun(this.$refs.konvers.innerHTML),                       
                            ],
                        }),
                    ],
                },
                ],
            });
            const mimeType =
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
            const fileName = `${this.profileName}_report.docx`;
            await Packer.toBlob(doc).then((blob) => {
                const docblob = blob.slice(0, blob.size, mimeType);
                FileSaver.saveAs(docblob, fileName);
            });

            this.load = false
        },
        async getReport() {
            if (!this.start_date || !this.finish_date) {
                alert('Укажите дату')
            }
            else {
                this.report = null,
                    this.load = true,

                    await axios.get(`https://api-metrika.yandex.net/stat/v1/data?metrics=ym:s:visits,ym:s:pageviews,ym:s:pageDepth,ym:s:bounceRate,ym:s:avgVisitDurationSeconds,ym:s:newUserVisitsPercentage&dimensions=ym:s:deviceCategory&date1=${this.start_date}&date2=${this.finish_date}&ids=${this.profile}`, {
                        headers: {
                            'Authorization': `OAuth ${this.integr.auth_token}`,
                        }
                    }).then(res => {
                        this.report = res.data.totals
                        this.demis = res.data.data
                    }).catch(err => console.log(err))
                        .finally(() => {
                            this.load = false
                            this.getGoals()
                            this.getTraffic()
                        })
            }

        }
    },
    mounted() {
        this.getProject()
    }
}
</script>
<style>
.report_title {
    font-size: 22px;
    font-weight: 700;
}

.report_date {
    font-size: 18px;
    margin-top: 5px;
}

.report_main {
    margin-top: 30px;
}
.report_stolb {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}
.report_main_indicators {
    display: flex;
    margin: 30px 0;
    flex: 0 1 100%;
    justify-content: space-between;
    flex-wrap: wrap;
}
.report_stolb_diagram {
    flex: 0 1 100%;
}
.report_indicators {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    margin-top: 15px;
    margin-bottom: 30px;
}
.report_indicators .indictr{
    flex: 0 1 31.333%;
    padding: 15px;
}
.indictr {
    margin-bottom: 15px;
    padding: 15px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    flex: 0 1 31.3333%;
}

.indictr_name {
    font-size: 16px;
}

.indictr_desc {
    font-size: 12px;
    color: #a6a6a6;
}
.indictr_int {
    font-size: 18px;
    font-weight: 700;
    text-align: end;
}

.report_waves {
    margin-bottom: 20px;
}

.wave {
    margin-bottom: 20px;
}

.report_desc {
    font-size: 14px;
    margin: 5px 0;
}

.report_descs {
    margin-bottom: 30px;
}

</style>