<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Manager user</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Fullname</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in list" :key="i">
                                    <th>{{ i + 1 }}</th>
                                    <td>{{ item.username }}</td>
                                    <td>{{ item.fullname }}</td>
                                    <td>{{ item.phone }}</td>
                                    <td>{{ item.email }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-6 col-md-4">
                                                <button
                                                    data-toggle="modal"
                                                    :data-target="`#changeInfo${item.id}`"
                                                    class="btn btn-outline-danger btn-block"
                                                >Change info</button>
                                                <change-info @onChange="getUser" :item="item" />
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <button
                                                    data-toggle="modal"
                                                    :data-target="`#sendMessage${item.id}`"
                                                    class="btn btn-outline-primary btn-block"
                                                >Send message</button>
                                                <send-message :item="item" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import ChangeInfo from '@/components/ChangeInfo.vue'
import SendMessage from '@/components/SendMessage.vue'
import { useUserStore } from '@/stores/user'
import { storeToRefs } from 'pinia'
import { toRef, watch } from 'vue'
const props = defineProps({
    propAccount: {
        type: Object,
        required: true
    }
})
document.title = 'Manager'
const account = toRef(props, 'propAccount')
const user = useUserStore()

const getUser = async () => {
    if (!await user.get()) {
        sweetAlert('Oops...', user.log, 'error')
    }
}

const getUserWithType = async type => {
    if (!['student', 'teacher', 'admin'].includes(type)) return
    await getUser()
}

watch(account, (v) => getUserWithType(v.type))
getUserWithType(account.value.type)

const { list } = storeToRefs(user)
</script>