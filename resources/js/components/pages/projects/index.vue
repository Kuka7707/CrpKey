<template>

    <div class="container">
        <div class="text-center" v-if="load">
            <div class="spinner-border text-info" style="width: 5rem; height: 5rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div class="row mt-3" v-if="!load">
            <h1>Проекты</h1>
            <div class="d-flex align-items-start">
                <div class="nav flex-column nav-pills me-5" id="v-pills-tab" role="tablist" aria-orientation="vertical">

                    <button  v-for="project in projects" class="nav-link" :key="project.id" :id="'v-pills-' + project.slug + '-tab'" data-bs-toggle="pill" :data-bs-target="'#v-pills-' + project.slug" type="button" role="tab" :aria-controls="'v-pills-'+ project.slug" aria-selected="false">{{project.name}}</button>

                </div>
                <div class="tab-content" id="v-pills-tabContent"> 

                    <div v-for="pro in projects" class="tab-pane fade" :key="pro.id" :id="'v-pills-' + pro.slug" role="tabpanel" :aria-labelledby="'v-pills-'+ pro.slug +'-tab'">
                        <h3>Интеграции</h3>
                        <div class="row mt-3">
                            <div class="col-lg-4 mb-3" v-for="inter in pro.integration" :key="inter.id">
                                <div class="card" style="width: 15rem;">
                                    <router-link :to="{name:'ShowIntegration', params:{slug: inter.slug}}" class="card-body">
                                        <h4 class="card-title">{{inter.name}}</h4>
                                    </router-link>
                                    <!-- <button type="button" class="btn btn-danger" @click="deleteDesk(desk.id)">Удалить</button> -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
export default {
    data(){
        return{
            projects:[],
            load: true,
            errored: false
        }
    },
    methods: {
        getProjects(){
            axios.get('/api/projects').then(response => {
                this.projects = response.data.data;
            }).catch(error => {
                console.log(error)
                this.errored = true;
            }).finally(() => {
                this.load = false;
            })
        }
    },
    mounted(){
        this.getProjects()
    }
}
</script>
<style>
    .nav-pills .nav-link{
        padding: 10px 80px;
    }
    .card-body{
        text-decoration: none;
        color: black;
    }
    .card-body:hover{
        color: red;
    }
</style>