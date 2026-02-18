import { createApp } from 'vue';
import Dashboard from './components/Dashboard.vue';
import './bootstrap';
import '../css/dashboard.css';

const app = createApp(Dashboard);
app.mount('#dashboard-app');
