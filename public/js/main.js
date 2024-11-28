    // Función para manejar el scroll
    function handleScroll() {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 0) {
            navbar.classList.remove('bg-black');
            navbar.classList.add('bg-black/90', 'backdrop-blur-sm');
        } else {
            navbar.classList.remove('bg-black/90', 'backdrop-blur-sm');
            navbar.classList.add('bg-black');
        }
    }

    // Agregar el evento de scroll
    window.addEventListener('scroll', handleScroll);
    
    // Ejecutar una vez al cargar para establecer el estado inicial
    handleScroll();
    const deviceChartCtx = document.getElementById('deviceChart').getContext('2d');
    const monthlyChartCtx = document.getElementById('monthlyChart').getContext('2d');
    const responseChartCtx = document.getElementById('responseChart').getContext('2d');

    // Chart.js for device incidents
    const deviceChart = new Chart(deviceChartCtx, {
      type: 'pie',
      data: {
        labels: ['Dispositiu A', 'Dispositiu B', 'Dispositiu C', 'Dispositiu D'],
        datasets: [{
          data: [12, 19, 6, 3],
          backgroundColor: ['#FF9999', '#66B2FF', '#99FF99', '#FFCC99'],
        }]
      },
      options: {
        responsive: true,
      }
    });

    // Chart.js for monthly incidents
    const monthlyChart = new Chart(monthlyChartCtx, {
      type: 'bar',
      data: {
        labels: ['Gener', 'Febrer', 'Març', 'Abril', 'Maig', 'Juny', 'Juliol', 'Agost', 'Setembre', 'Octubre', 'Novembre', 'Desembre'],
        datasets: [{
          label: 'Incidències',
          data: [15, 20, 25, 18, 22, 17, 19, 23, 20, 21, 18, 20],
          backgroundColor: '#4CAF50',
        }]
      },
      options: {
        responsive: true,
      }
    });

    // Chart.js for response time
    const responseChart = new Chart(responseChartCtx, {
      type: 'line',
      data: {
        labels: ['Día 1', 'Día 2', 'Día 3', 'Día 4', 'Día 5'],
        datasets: [{
          label: 'Tiempo de Respuesta (h)',
          data: [5, 6, 4, 7, 3],
          fill: false,
          borderColor: '#FF6347',
          tension: 0.1,
        }]
      },
      options: {
        responsive: true,
      }
    });