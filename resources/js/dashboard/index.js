"use strict";

// Class definition
var InitDashboard = function () {
    // Define colors
    var primaryColor = '#009ef7';
    var dangerColor = '#f1416c';
    var successColor = '#50cd89';
    let monthlyChartInstance; // Store the chart instance
    let weeklyChartInstance; // Store the chart instance

    const getData = async (__form, __endpoint, __title1, __title2, __title3) => {
        const form = document.querySelector(__form);

        if (!form) {
            return;
        }

        var formData = new FormData(form); // Get form data

        const labels = [];
        const counter1 = [];
        const counter2 = [];
        const counter3 = [];

        await fetch(__endpoint, {
                method: "POST",
                headers: {
                    'Authorization': `Bearer ${apiToken}`,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            }).then((res) => res.json())
            .then((data) => {
                data.data.forEach(c => {
                    labels.push(c.label);
                    counter1.push(c.counter1);
                    counter2.push(c.counter2);
                    counter3.push(c.counter3);
                });
            });

        const results = {
            labels: labels,
            datasets: [
                {
                    label: __title1,
                    data: counter1,
                    borderColor: successColor,
                    backgroundColor: successColor
                },
                {
                    label: __title2,
                    data: counter2,
                    borderColor: primaryColor,
                    backgroundColor: primaryColor
                },
                {
                    label: __title3,
                    data: counter3,
                    borderColor: dangerColor,
                    backgroundColor: dangerColor
                },
            ]
        };

        return results;
    }

    var weeklyChart = function () {
        const form = document.querySelector('#weekly-form');
        const year = form.querySelector('#weekly-year');
        const week = form.querySelector('#weekly-week');
        const loadingElement = form.querySelector('.loading');
        const chartElement = form.querySelector('#weekly-chart');
      
        if (!form || !chartElement) {
            return;
        }

        const showLoading = () => {
            if (loadingElement) {
                loadingElement.style.display = 'block';
            }
        };

        const hideLoading = () => {
            if (loadingElement) {
                loadingElement.style.display = 'none';
            }
        };

        const updateChart = (results) => {
            if (weeklyChartInstance) {
                weeklyChartInstance.destroy(); // Destroy the existing chart instance
            }

            var config = {
                type: 'line',
                data: results,
                options: {
                    plugins: {
                        title: {
                            display: false,
                         },
                        tooltip: {
                            enabled: true,
                            mode: 'nearest',
                            intersect: false
                        }
                    },
                    responsive: true,
                }
            };

            var ctx = chartElement.getContext('2d');
            weeklyChartInstance = new Chart(ctx, config);
        };

        showLoading();

        getData('#weekly-form', APP_URL + '/api/transactions/charts?chartType=weekly&year=' + year.value + '&week=' + week.value, 'Weekly Transaction', 'Weekly Completed Tasks', 'Weekly Cancelled Tasks')
            .then(results => {
                hideLoading();
                updateChart(results);
            })
            .catch(error => {
                hideLoading();
                console.error('An error occurred while fetching weekly transactions:', error);
            });
    };

    var monthlyChart = function () {
        const form = document.querySelector('#monthly-form');
        const year = form.querySelector('#monthly-year');
        const loadingElement = form.querySelector('.loading');
        const chartElement = form.querySelector('#monthly-chart');
      
        if (!form || !chartElement) {
            return;
        }

        const showLoading = () => {
            if (loadingElement) {
                loadingElement.style.display = 'block';
            }
        };

        const hideLoading = () => {
            if (loadingElement) {
                loadingElement.style.display = 'none';
            }
        };

        const updateChart = (results) => {
            if (monthlyChartInstance) {
                monthlyChartInstance.destroy(); // Destroy the existing chart instance
            }

            var config = {
                type: 'line',
                data: results,
                options: {
                    plugins: {
                        title: {
                            display: false,
                        },
                        tooltip: {
                            enabled: true,
                            mode: 'nearest',
                            intersect: false
                        }
                    },
                    responsive: true,
                }
            };

            var ctx = chartElement.getContext('2d');
            monthlyChartInstance = new Chart(ctx, config);
        };

        showLoading();

        getData('#monthly-form', APP_URL + '/api/transactions/charts?chartType=monthly&year=' + year.value, 'Monthly Transaction', 'Monthly Completed Tasks', 'Monthly Cancelled Tasks')
             .then(results => {
                hideLoading();
                updateChart(results);
            })
            .catch(error => {
                hideLoading();
                console.error('An error occurred while fetching monthly summary:', error);
            });
    };

    return {
        init: function () {
            Chart.defaults.font.size = 13;
            Chart.defaults.font.family = KTUtil.getCssVariableValue('--bs-font-sans-serif');

            weeklyChart();
            monthlyChart();

            const monthlyTransYearSelect = document.querySelector('#monthly-year');
            monthlyTransYearSelect.addEventListener('change', () => {
                monthlyChart(); // Call the function again to fetch and update the chart with new data
            });

            ['year', 'week_number'].forEach(element => {
                const reload = document.querySelector('#weekly-form').querySelector('select[name="' + element + '"]');
                if (reload) {
                    reload.addEventListener('change', function (e) {
                        weeklyChart(); // Call the function again to fetch and update the chart with new data
                    });
                };
            });
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    InitDashboard.init();
});
