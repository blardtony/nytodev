import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/layout.css';
import './styles/button.css';
import './styles/alert.css';
import './styles/page/auth.css';
import './styles/form.css';

const closeAlertButtons = document.querySelectorAll('.alert .close');
closeAlertButtons.forEach((button) => {
    button.addEventListener('click', () => {
        button.parentElement.classList.add('out');
        setTimeout(() => {
            button.parentElement.remove();
        }, 500);
    });
});
