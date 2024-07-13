import './styles/styles.scss';
import Alpine from 'alpinejs';
import Focus from "@alpinejs/focus"; // optional unless you want to use x-trap
import AlpineFloatingUI from "@awcodes/alpine-floating-ui";
import beacon from './beacon';

window.Alpine = Alpine;
Alpine.plugin(Focus); // optional unless you want to use x-trap
Alpine.plugin(AlpineFloatingUI);
Alpine.data('beacon', beacon);
Alpine.start();
