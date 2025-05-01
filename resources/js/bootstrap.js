import axios from 'axios';

import $ from 'jquery';
window.$ = window.jQuery = $;
// console.log($('meta[name=base_url]').prop('content'))
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}
