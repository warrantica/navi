export default {
  getAllFunds(){
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'GET',
        url: '/api/fund',
        success: data => resolve(data),
        error: () => reject('getAllFunds() error')
      });
    });
  },

  addFund(data){
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'POST',
        url: '/api/fund',
        data: data,
        success: data => {
          if(data.success) resolve();
          else reject('addFund() error');
        },
        error: (xhr, status, data) => reject(xhr.responseJSON.errors)
      });
    });
  },

  getNav(name){
    return new Promise((resolve, reject) => {
      if(!name) reject('Name must not be empty');
      $.ajax({
        type: 'GET',
        url: '/api/fund/' + name,
        success: data => resolve(data),
        error: () => reject('getNav() for ' + name + ' error')
      });
    });
  },

  getHistoricalNav(name){
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'GET',
        url: '/api/history/' + name,
        success: data => resolve(data),
        error: () => reject('getHistoricalNav() for ' + name + ' error')
      });
    });
  },

  getHistoricalChartData(name, numberOfMonths){
    return new Promise((resolve, reject) => {
      $.ajax({
        type: 'GET',
        url: '/api/chart/' + name + '/' + numberOfMonths,
        success: data => resolve(data),
        error: () => reject('getHistoricalChartData() for ' + name + ' error')
      });
    });
  }
}
