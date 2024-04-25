<script setup>
    import { ref } from 'vue'

    import Axios from 'axios'

    const homeUrl = ref('');

    const saveButtonText = ref('Save Settings');
    const responseText = ref();

    let url = msoAdminLocalizer.apiUrl + '/mso/v1/store-settings'

    const form = ref([
        {
            name: 'token_key',
            value: '',
        },
        {
            name: 'whitelisted_domains',
            value: '',
        },
    ])

    const saveSettings = async (e) => {
        saveButtonText.value = 'Saving...';
        await Axios.post(url, {
            token_key: form.value[0].value,
            whitelisted_domains: form.value[1].value,
        })
            .then((response) => {
                responseText.value = response.data.message
                console.log(response.data)
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
                form.value[0].value = response.data.token_key
                form.value[1].value = response.data.whitelisted_domains
            })
            .catch((error) => {
                console.log(error)
            })
    }

    const getHostUrl = () => {
        const { protocol, hostname, port } = window.location;
        homeUrl.value = `${protocol}//${hostname}${port ? `:${port}` : ''}`;
    };
    getHostUrl()
    getSettings()
</script>

<template>
    <h1>Store Settings</h1>
    
    <div id="mso-general-setting-tab" class="tab-container">
        <div v-if="responseText" class="notice notice-success" style="padding: 10px 5px;"><strong>{{ responseText }}</strong></div>
        <form id="mso-general-setting-form" @submit.prevent="saveSettings" method="POST">
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row">
                            Webhook: 
                        </th>
                        <td>
                            <strong>{{ homeUrl }}/wp-json/mso/v1/store-order</strong>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="token-key">Token Key</label>
                        </th>
                        <td>
                            <input id="token-key" class="regular-text" v-model="form[0].value" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="domains">White Listed Domain(s)</label>
                        </th>
                        <td>
                            <textarea id="domains" class="regular-text" v-model="form[1].value"></textarea> <br>
                            <em>Add domain name seperated by comman (,)</em>
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
