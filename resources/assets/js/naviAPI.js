export default {
  getNav(name){
    return new Promise((resolve, reject) => {
      if(!name) reject('Name must not be empty');
      $.ajax({
        type: 'GET',
        url: '/api/fund/' + name,
        success: data => resolve(data),
        error: () => reject('getNav() error')
      });
    });
  },

  getHistoricalNav(name){
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'GET',
        url: '/api/history/' + name,
        success: data => resolve(data),
        error: () => reject('getHistoricalNav() error')
      });
    });
  },

  getHistoricalChartData(name){
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'GET',
        url: '/api/chart/' + name,
        success: data => resolve(data),
        error: () => reject('getHistoricalChartData() error')
      });
    });
  }
}
