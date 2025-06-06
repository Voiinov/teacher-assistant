
/* global moment:false, Chart:false, Sparkline:false */

$(function () {
    'use strict'
  
    // Make the dashboard widgets sortable Using jquery UI
    $('.connectedSortable').sortable({
      placeholder: 'sort-highlight',
      connectWith: '.connectedSortable',
      handle: '.card-header, .nav-tabs',
      forcePlaceholderSize: true,
      zIndex: 999999
    })
    
    $('.connectedSortable .card-header').css('cursor', 'move')

  })

// Функція для оновлення прогрес-бару і часу, що залишився
function progressBarTimer(){

  const currentTime = new Date(); // Поточний час
  const totalDuration = endTime - startTime; // Загальна тривалість у мілісекундах
  const elapsedDuration = currentTime - startTime; // Пройдено часу від початку
  let progressPercentage = (elapsedDuration / totalDuration) * 100;

  // Обмежити відсотки в межах 0-100
  progressPercentage = Math.max(0, Math.min(progressPercentage, 100));

  // Оновлення прогрес-бару
  $("#timer")
      .css('width', progressPercentage + '%')
      .attr('aria-valuenow', progressPercentage);

  // Розрахунок часу, що залишився
  const timeLeft = endTime - currentTime;
  if (timeLeft > 0) {
      const minutesLeft = Math.floor(timeLeft / 1000 / 60); // Залишилось хвилин
      // const minutesLeft = Math.floor(timeLeft / 1000 / 60) % 60; // Залишилось хвилин
      // const hoursLeft = Math.floor(timeLeft / 1000 / 60 / 60); // Залишилось годин

      // Оновлення тексту про залишений час
      $("#timer").find("span").text(`Залишилось: ${minutesLeft} хвилин(и)`);
  } else {
      // Якщо час завершився
      $("#timer").find("span").text('Урок закінчився!');
  }
}
