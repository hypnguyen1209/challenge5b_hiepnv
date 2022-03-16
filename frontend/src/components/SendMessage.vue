<template>
    <div class="modal fade" :id="`sendMessage${item.id}`">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send message to {{ item.username }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label">Content:</label>
                        <div class="col-sm-12">
                            <textarea
                                v-model="messageArea"
                                class="form-control"
                                rows="4"
                                placeholder="Type something..."
                            ></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        ref="closeModal"
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >Close</button>
                    <button
                        ref="btnSave"
                        type="button"
                        class="btn btn-primary"
                        disabled
                        @click="send"
                    >Send</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { toRef, ref, watch } from "vue"
import api from '@/services/api'
const props = defineProps({
    item: {
        type: Object,
        required: true
    }
})
const item = toRef(props, 'item')
const messageArea = ref('')
const btnSave = ref(null)
const closeModal = ref(null)

const send = async () => {
    let req = await api.post(`/send_message/${item.value.id}`, {
        message: messageArea.value
    })
    if (req.status) {
        closeModal.value.click()
        messageArea.value = ''
        swal('Message has been sent!', '', 'success')
    } else {
        sweetAlert('Oops...', req.message, 'error')
    }
}
watch(messageArea, v => {
    btnSave.value.disabled = v === ''
})
</script>