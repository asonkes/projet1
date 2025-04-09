import './styles/index.scss';
import 'bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const headerBurger = document.querySelector('.icon_burger');
    const headerCross = document.querySelector('.icon_cross');
    const burger = document.querySelector('.burger');
    
    headerBurger.addEventListener('click', () => {
        burger.classList.add('active');
        headerCross.classList.add('active');
        headerBurger.classList.add('active');

        
    });
    
    headerCross.addEventListener('click', () => {
        burger.classList.remove('active');
        headerCross.classList.remove('active');
        headerBurger.classList.remove('active');
    })

    document.addEventListener('click', (event) => {
        // Vérifier si le clic est à l'extérieur du burger
        if (!burger.contains(event.target) && !headerBurger.contains(event.target)) {
            burger.classList.remove('active');
            headerCross.classList.remove('active');
            headerBurger.classList.remove('active');
        }
    });
})