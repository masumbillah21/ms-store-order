<script setup>
    import { ref } from 'vue'
    import Axios from 'axios';

    let url = msoAdminLocalizer.apiUrl + '/mso/v1/hub-settings'
    const saveButtonText = ref('Save Settings');
    const responseText = ref();

    const form = ref([
        {
            name: 'webhook',
            value: '',
        },
        {
            name: 'token-key',
            value: '',
        },
    ])

    const saveSettings = async (e) => {
        saveButtonText.value = 'Saving...';
        await Axios.post(url, {
            webhook_url: form.value[0].value,
            hub_token_key: form.value[1].value,
        })
            .then((response) => {
                responseText.value = response.data.message
                console.log(response)
            })
            .catch((error) => {
                console.log(error)
            }).finally(() => {
                saveButtonText.value = 'Save Settings';
            })
    }

    const getSettings = (e) => {
        Axios.get(url)
            .then((response) => {
                form.value[0].value = response.data.webhook_url
                form.value[1].value = response.data.hub_token_key
                console.log(response.data)
            })
            .catch((error) => {
                console.log(error)
            })
    }
    getSettings()
</script>

<template>
    <h1>Hub Settings</h1>
    <div id="mso-general-setting-tab" class="tab-container">
        <div v-if="responseText" class="notice notice-success" style="padding: 10px 5px;"><strong>{{ responseText
                }}</strong></div>
        <form id="mso-general-setting-form" @submit.prevent="saveSettings">
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="webhook">Webhook Url</label>
                        </th>
                        <td>
                            <input id="webhook" class="regular-text" v-model="form[0].value" type="url">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="token-key">Token Key</label>
                        </th>
                        <td>
                            <input id="token-key" class="regular-text" type="text" v-model="form[1].value">
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="submit">
                <button type="submit" class="button button-primary">{{ saveButtonText }}</button>
            </p>
        </form>
        <div class="clear"></div>
    </div>
</template>
