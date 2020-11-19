import Axios from "axios";

const axios = Axios.create();

axios.defaults

axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

export {axios};