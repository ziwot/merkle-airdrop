declare var bootstrap: any;
import 'bootstrap'
// @ts-expect-error miss type declaration
import htmx from 'htmx.org'
import Alpine from 'alpinejs';
import Focus from "@alpinejs/focus"; // optional unless you want to use x-trap
// @ts-expect-error miss type declaration
import AlpineFloatingUI from "@awcodes/alpine-floating-ui";
import beacon from './beacon';

Array.prototype.slice.call(document.querySelectorAll('.alert'))
    .map((element: Element) => new bootstrap.Alert(element))

window.Alpine = Alpine;
window.htmx = htmx;
Alpine.plugin(Focus); // optional unless you want to use x-trap
Alpine.plugin(AlpineFloatingUI);
document.addEventListener('alpine:init', () => {
    Alpine.data('beacon', beacon);
});

Alpine.start();
