<template>
    <div class="">
        <Navbar/>
        <section class="hero">
            <div class="container">
                <div class="hero-body">
                    <div class="card">
                        <header class="card-header">
                            <p class="card-header-title">
                                All Users
                            </p>
                            <button class="card-header-icon" title="Fetch Users" @click="fetchUsersApi()">
                                <span class="icon">
                                  <i class="fas fa-download" aria-hidden="true"></i>
                                </span>
                                <span>Fetch</span>
                            </button>
                        </header>
                        <div class="card-content">
                            <div class="content">
                                <table class="table is-fullwidth">
                                    <thead>
                                    <tr style="text-align: center">
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(user, i) in users" :key="i">
                                        <td v-html="user.user_name"></td>
                                        <td>
                                            <button class="button is-info is-small" title="Details"
                                                    @click="showUserDetails(user)">
                                                <span class="icon">
                                                    <i class="fa fa-search"></i>
                                                </span>
                                            </button>
                                            <button class="button is-primary is-small" title="Edit"
                                                    @click="editUserDetails(user)">
                                                <span class="icon">
                                                    <i class="fa fa-edit"></i>
                                                </span>
                                            </button>
                                            <button class="button is-danger is-small" title="Delete"
                                                    @click="deleteUser(user.identifier)">
                                                <span class="icon">
                                                    <i class="fa fa-trash"></i>
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <modal :is_active="user_details_modal_show" @closeModal="user_details_modal_show = false">
            <template v-slot:header>
                <h1>User Details</h1>
            </template>
            <table class="table is-fullwidth">
                <tr>
                    <th>Name:</th>
                    <td>{{ show_user_details.user_name }}</td>
                </tr>
                <tr>
                    <th>Email:</th>
                    <td>{{ show_user_details.user_email }}</td>
                </tr>
                <tr>
                    <th>Gender:</th>
                    <td>{{ show_user_details.user_gender }}</td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>{{ show_user_details.status }}</td>
                </tr>
            </table>
        </modal>

        <modal :is_active="user_edit_modal_show" @closeModal="user_edit_modal_show = false">
            <template v-slot:header>
                <h1>User Update</h1>
            </template>
            <form>
                <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                        <input class="input" v-model="edit_user_details.user_name" type="text" placeholder="Name">
                    </div>
                </div>

                <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                        <input class="input" v-model="edit_user_details.user_email" type="text" placeholder="Email">
                    </div>
                </div>

                <div class="field">
                    <label class="label">Email</label>
                    <div class="select">
                        <select v-model="edit_user_details.user_gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <label class="label">Status</label>
                    <div class="control">
                        <label class="radio">
                            <input type="radio" name="status" v-model="edit_user_details.status" value="active"> Active
                        </label>
                        <label class="radio">
                            <input type="radio" name="status" v-model="edit_user_details.status" value="inactive"> Inactive
                        </label>
                    </div>
                </div>

            </form>


            <template v-slot:footer>
                <button class="button is-success" @click="updateUserDetails">Save changes</button>
            </template>
        </modal>
    </div>
</template>

<script>
import Vue from 'vue'
export default {
    name: "App",
    data() {
        return {
            fetchUsers: [],
            users: [],
            user_details_modal_show: false,
            user_edit_modal_show: false,
            show_user_details: {},
            edit_user_details: {}
        }
    },
    components: {
        Navbar: () => import('./Navbar'),
        Modal: () => import('./Modal'),
    },
    created() {
        this.getUsers()
    },
    methods: {
        fetchUsersApi() {
            axios.get('https://gorest.co.in/public/v1/users')
                .then((response) => {
                    this.fetchUsers = response.data.data
                    this.loadUsers()
                })
        },
        loadUsers() {
            axios.post('api/users', {'users': this.fetchUsers})
                .then((response) => {
                    this.getUsers()
                })
        },
        getUsers() {
            axios.get('api/users')
                .then((response) => {
                    this.users = response.data.data
                })
        },
        showUserDetails(user) {
            this.show_user_details = user
            this.user_details_modal_show = true
        },
        editUserDetails(user) {
            this.edit_user_details = user
            this.user_edit_modal_show = true
        },
        updateUserDetails() {
            axios.patch(`api/users/${this.edit_user_details.identifier}`, {
                'user_name': this.edit_user_details.user_name,
                'user_email': this.edit_user_details.user_email,
                'user_gender': this.edit_user_details.user_gender,
                'status': this.edit_user_details.status,
            })
                .then((response) => {
                    const index = this.users.map(user => user.identifier).indexOf(this.edit_user_details.identifier);
                    if (index > -1) Vue.set(this.users, index, this.edit_user_details);
                    this.user_edit_modal_show = false
                })
        },
        deleteUser(userId) {
            if (confirm('Are you sure to delete!!')) {
                axios.delete(`api/users/${userId}`)
                    .then((response) => {
                        const index = this.users.map(user => user.identifier).indexOf(userId);
                        if (index > -1) this.users.splice(index, 1);
                    })
            }
        }
    }
}
</script>

<style scoped>

</style>
