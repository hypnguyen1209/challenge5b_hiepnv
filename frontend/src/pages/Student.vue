<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Table Students</h4>
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
                                        <button
                                            data-toggle="modal"
                                            :data-target="`#changeInfo${item.id}`"
                                            class="btn btn-outline-danger btn-block"
                                        >Change Info</button>
                                        <change-info @onChange="getStudent" :item="item" />
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
import { useStudentStore } from '@/stores/student'
import { storeToRefs } from 'pinia'
import { toRef, watch } from 'vue'
const props = defineProps({
    propAccount: {
        type: Object,
        required: true
    }
})
const account = toRef(props, 'propAccount')
const student = useStudentStore()
document.title = 'Student'
const getStudent = async () => {
    if (!await student.get()) {
        sweetAlert('Oops...', student.log, 'error')
    }
}

const getStudentWithType = async type => {
    if (!['student', 'teacher', 'admin'].includes(type)) return
    await getStudent()
}

watch(account, (v) => getStudentWithType(v.type))
getStudentWithType(account.value.type)

const { list } = storeToRefs(student)
</script>