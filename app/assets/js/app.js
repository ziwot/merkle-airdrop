import Alpine from 'alpinejs';
import beacon from './beacon';

window.Alpine = Alpine;
Alpine.data('beacon', beacon);
Alpine.start();
