<template>
    <div>
        <h3 class="mb-1">Add a new member to your team:</h3>
        <form method="POST" :action="formPath">
            <div class="col-xs-4">
                <input type="hidden" name="_token" :value="csrfToken">
                <input type="hidden" name="user_id" v-model="userId">
                <input type="text"
                       name="name"
                       class="form-control mb-1"
                       placeholder="Search for member"
                       v-model="name"
                       @input="search"
                       :disabled="userId != null">

                <ul class="list-group mt--1 bt-g" v-show="results.length">
                    <li class="list-group-item" @click="choose(result)" v-for="result in results">{{ result.name }}</li>
                </ul>

                <button class="btn btn-primary form-control">Add member</button>
            </div>
        </form>
    </div>
</template>

<style>
    .mt--1 {margin-top: -1em;}
    .bt-g { border-top: grey; border-radius: 4px; }
    .list-group-item:hover, .list-group-item:active {background: #eee; font-weight: bold}
</style>

<script>
    export default {
        props: ['team'],

        data () {
            return {
                name: '',
                userId: null,
                results: []
            };
        },
        methods: {
            search (e) {
                axios.get(`/users/${e.target.value}`)
                    .then((response) => {
                        this.results = response.data;
                    });
            },

            choose (result) {
                this.userId = result.id;
                this.results = [];
                this.name = result.name;
            }
        },
        computed: {
            formPath () {
                return `/memberships/${this.team.id}`;
            },

            csrfToken () {
                return document.head.querySelector('meta[name="csrf-token"]').content;
            }
        }
    }
</script>
