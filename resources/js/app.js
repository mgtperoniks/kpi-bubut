import './bootstrap';
import Chart from 'chart.js/auto';
import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

// Local Bundling (jQuery & Select2)
import jQuery from 'jquery';
import select2 from 'select2';
import 'select2/dist/css/select2.min.css';

window.$ = window.jQuery = jQuery;
select2(); // Initialize Select2

window.Alpine = Alpine;
window.Chart = Chart;
window.Swal = Swal;

Alpine.start();
