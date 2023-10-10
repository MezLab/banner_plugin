function Graphic_Campaign(name, url_){

      let _months = [];
      const banners = [];
      const clicks = [];


      const d = {
            labels: [],
            datasets: [],
      }

      

      async function fetchData() {
        const url = url_ + '/wp-content/plugins/mezcraft/Media/Chart/' + name + '.json';

        try {
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error('Errore nel recupero dei dati');
            }

            const datapoints = await response.json();
            return datapoints;
        } catch (error) {
            console.error('Si è verificato un errore:', error);
            return null;
        }
    }

    
      fetchData().then(datapoints => {
          if (datapoints) {

              const data_1 = datapoints.data.map((x) => {
                _months = x.month;
                const c_1 = x.activity.map((x) => {
                  banners.push(x.banner);
                  clicks.push(x.click);
                })                
              });
          }

          graphic();
      });


      function graphic() {

          const d = {
          labels: _months,
          datasets: []
          };

          for (let a = 0; a < banners.length; a++) {
              d.datasets.push({
                  label: banners[a],
                  data: clicks[a],
                  borderWidth: 2
              });
          }

          const ctx = document.getElementById('myChart');
          // Il grafico viene letto tramite json
          new Chart(ctx, {
            type: 'line',
            data: d,
            options: {
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
      }
}
      