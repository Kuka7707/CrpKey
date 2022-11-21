<template>

    <div>
        <b-navbar toggleable="lg" type="dark" variant="dark">
            <b-collapse id="nav-collapse" is-nav>
            <b-navbar-nav class="container">
                <router-link :to="{name:'home'}" class="nav-link">Инструкция</router-link>
                <router-link :to="{name:'projects'}" class="nav-link">Проекты</router-link>
            </b-navbar-nav>
            </b-collapse>
        </b-navbar>
        <div>
            <router-view></router-view>
        </div>        
    </div>

    
</template>

<script>
export default {
    data(){
        return {
            
        }
    },
    mounted(){
        
    },
    methods:{
       
    },
    updated(){
        
    }
}
</script>
