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
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-primary"  @click="modal=true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg> Добавить новый
                    </button>
                </div>
                <div class="tab-content" id="v-pills-tabContent"> 

                    <div v-for="pro in projects" class="tab-pane fade" :key="pro.id" :id="'v-pills-' + pro.slug" role="tabpanel" :aria-labelledby="'v-pills-'+ pro.slug +'-tab'">
                        <div class="integr">
                            <div class="inger_itm">
                                <h3>Интеграции</h3>
                            </div>
                            <div class="inger_itm">
                                <button type="button" class="btn btn-primary" @click="modal_ingr=true">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg> Добавить
                                </button>
                                <button type="button" class="btn btn-danger" @click="deleteProject(pro.name, pro.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg> Удалить
                                </button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-lg-4 mb-3" v-for="inter in pro.integration" :key="inter.id">
                                <div class="card" style="width: 15rem;">
                                    <router-link :to="{name:'ShowIntegration', params:{slug: inter.slug}}" class="card-body">
                                        <h4 class="card-title">{{inter.name}}</h4>
                                    </router-link>
                                    <button type="button" class="btn btn-danger" @click="deleteIngr(inter.id)">Удалить</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Modal -->
        <div v-if="modal" class="modal fade show" id="addProject" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addProjectLabel" aria-modal="true" role="dialog" style="display: block;background: #404040a6;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProjectLabel">Добавить новый проект</h5>
                        <button type="button" v-on:click="modal=false" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="errored" class="alert alert-danger" role="alert">
                            {{err_text}}
                        </div>
                        <div class="modal_item">
                            <label for="name">Название</label>
                            <input type="text" v-model="name" name="name">
                        </div>
                        <div class="modal_item">
                            <label for="desc">Описание</label>
                            <input type="text" v-model="description" name="desc">
                        </div>
                        <div class="modal_item">
                            <label for="url">URL</label>
                            <input type="text" v-model="slug" name="url">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="postProject()" class="btn btn-primary">Создать</button>
                        <button type="button" @click="modal=false" class="btn btn-secondary" data-bs-dismiss="modal">закрыть</button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="modal_ingr" class="modal fade show" id="addIntgr" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addIntgrLabel" aria-modal="true" role="dialog" style="display: block;background: #404040a6;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProjectLabel">Добавить новую интеграцию</h5>
                        <button type="button" v-on:click="modal_ingr=false" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="errored" class="alert alert-danger" role="alert">
                            {{err_text}}
                        </div>
                        <div class="modal_item">
                            <label for="name">Название</label>
                            <input type="text" v-model="intgrName" name="name">
                        </div>
                        <div class="modal_item">
                            <p>Выбор проекта:</p>
                            <select v-model="intgrProject">
                                <option disabled value="">Выберите один из вариантов</option>
                                <option v-for="project in projects" v-bind:value="project.id" :key="project.id">
                                    {{ project.name }}
                                </option>                
                            </select>
                        </div>
                        <div class="modal_item">
                            <label for="url">URL</label>
                            <input type="text" v-model="intgrSlug" name="url">
                        </div>
                        <!-- <div class="modal_item">
                            <label for="intgrClientId">CLIENT_ID</label>
                            <input type="text" v-model="intgrClientId" name="intgrClientId">
                        </div>
                        <div class="modal_item">
                            <label for="intgrClientSecret">CLIENT_SECRET</label>
                            <input type="text" v-model="intgrClientSecret" name="intgrClientSecret">
                        </div> -->
                        <div class="modal_item">
                            <label for="intgrAuthToken">Auth_Token</label>
                            <input type="text" v-model="intgrAuthToken" name="intgrAuthToken">
                        </div>
                        <a type="button" class="btn btn-primary" target="_blank" href="https://oauth.yandex.ru/authorize?response_type=token&amp;client_id=65506b92a1df480093dd75dd62ad3357">Получить Auth_Token</a>
                        
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="postIntegr()" class="btn btn-primary">Создать</button>
                        <button type="button" @click="modal_ingr=false" class="btn btn-secondary" data-bs-dismiss="modal">закрыть</button>
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
            errored: false,
            err_text:null,
            name:null,
            description:null,
            slug:null,
            modal:false,
            modal_ingr:false,
            intgrName:null,
            intgrProject:null,
            intgrSlug:null,
            intgrClientId:null,
            intgrClientSecret:null,
            intgrAuthToken:null
        }
    },
    methods: {
        getProjects(){
            axios.get('/api/projects').then(response => {
                this.projects = response.data.data;
            }).catch(error => {
                console.log(error)
            }).finally(() => {
                this.load = false;
            })
        },
        postProject(){
            var conf = {
                method: 'post',
                url: '/api/projects',
                headers: { 
                    'Content-Type': 'application/json'
                },
                data : JSON.stringify({
                    "name": this.name,
                    "description": this.description,
                    "slug": this.slug
                })
            };
            axios(conf).then(res=>{
                if(res.data.status == false){
                    if(res.data.errors.name){
                        this.err_text = "Назвение уже существует."
                        console.log(this.err_text)
                    }
                    else if(res.data.errors.slug){
                        this.err_text = "URL уже существует."
                        console.log(this.err_text)
                    }
                    this.errored = true
                }
                else{
                    this.modal = false
                    this.getProjects()
                }
                
            }).catch(err=>console.log(err))
        },
        deleteProject(name, id){
            if(confirm(`Вы хотите удалить Проект ${name} ?`)){
                axios.post('/api/projects/' + id, {
                    _method: 'DELETE'
                })
                .then(res=>{
                    this.load = true,
                    this.projects = [],
                    this.getProjects();
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true;
                })
                .finally(() => {
                    this.load = false;
                })
            }
        },
        postIntegr(){
            var conf = {
                method: 'post',
                url: '/api/integrations',
                headers: { 
                    'Content-Type': 'application/json'
                },
                data : JSON.stringify({
                    "name": this.intgrName,
                    "project_id": this.intgrProject,
                    "slug": this.intgrSlug,
                    "client_id": this.intgrClientId,
                    "client_secret": this.intgrClientSecret,
                    "auth_token": this.intgrAuthToken
                })
            };
            axios(conf).then(res => {
                console.log(res)
                this.modal_ingr = false
                this.getProjects()
            }).catch(err => {
                console.log(err);
            });
        },
        deleteIngr(id){
            if(confirm('Вы хотите удалить?')){
                axios.post('/api/integrations/' + id, {
                    _method: 'DELETE'
                })
                .then(res=>{
                    this.load = true,
                    this.projects = [],
                    this.getProjects();
                })
                .catch(error => {
                    console.log(error)
                    this.errored = true;
                })
                .finally(() => {
                    this.load = false;
                })
            }
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
        margin-bottom: 20px;
        border:solid 1px #0d6efd;
        border-radius: 10px;
    }
    #v-pills-tabContent{
        width: 100%;
    }
    .integr{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .card-body{
        text-decoration: none;
        color: black;
    }
    .card-body:hover{
        color: red;
    }
    .modal_item{
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }
    .modal_item label{
        padding: 0 10px 0;
        transform: translate(10px, 10px);
        background: #ffff;
        width: 30%;
        text-align: center;
    }
    .modal_item input, .modal_item select{
        padding: 10px;
        font-size: 16px;
        border: 1px solid #000;
        outline: none;
        border-radius: 5px;
    }
</style>