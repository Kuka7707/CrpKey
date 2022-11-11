<template>
    <div class="container">
        <h1 class="mt-3">{{name}}</h1>
        <div>Аккаунт: {{account}}</div>
        <div>
            Выборон профиль: 
            <select v-model="profile">
                <option disabled value="">Выберите один из вариантов</option>
                <option v-for="prof in profiles_list" v-bind:value="prof.id" :key="prof.id">
                    {{ prof.name }} ({{prof.id}})
                </option>                
            </select>
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
        }
    },
    mounted(){
        this.getProject()
    }
}
</script>
<style>
    
</style>