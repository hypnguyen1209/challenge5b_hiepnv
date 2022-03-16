<template>
    <div
        class="modal fade"
        :id="`modal_submited${item.id}`"
        aria-hidden="true"
        style="display: none;"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ item.submitted.length }} solved</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>UserId</th>
                                    <th>Name</th>
                                    <th>File</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr :key="i" v-for="(submit, i) in item.submitted">
                                    <th>{{ i + 1 }}</th>
                                    <td>{{ submit.userId }}</td>
                                    <td>{{ submit.username }}</td>
                                    <!-- submit.fullname -->
                                    <td>
                                        <a
                                            :href="submit.url"
                                            target="_blank"
                                            class="btn btn-outline-danger"
                                        >{{ submit.url.split('/').slice(-1)[0] }}</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { toRef } from "vue"

const props = defineProps({
    item: {
        type: Object,
        required: true
    }
})
const item = toRef(props, 'item')
</script>