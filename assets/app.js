import './bootstrap.js';
import './styles/index.scss';

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