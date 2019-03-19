$(document).ready(function(){
	$.ajax({
		url : "ChartData.php",
		type : "GET",
		success : function(data){
			console.log(data);

			var timeData = [];
			var temperatureData = [];
			var humidityData = [];
			var pressureData = [];
			var uvData = [];
			var airqualityData = [];

			for(var i in data) {
				timeData.push(data[i].time);
				temperatureData.push(data[i].temperature);
				humidityData.push(data[i].humidity);
				pressureData.push(data[i].pressure);
				uvData.push(data[i].uv);
				airqualityData.push(data[i].airquality)
			}

			var chartTemperature = {
				labels: timeData,
				datasets: [
					{
						fill: true,
						lineTension: 0.1,
						backgroundColor: "rgba(55, 66, 250, 0.75)",
						borderColor: "rgba(55, 66, 250, 1)",
						pointHoverBackgroundColor: "rgba(55, 66, 250, 1)",
						pointHoverBorderColor: "rgba(55, 66, 250, 1)",
						data: temperatureData
					}
				]
			};

			var chartHumidity = {
				labels: timeData,
				datasets: [
					{
						fill: true,
						lineTension: 0.1,
						backgroundColor: "rgba(30, 144, 255, 0.75)",
						borderColor: "rgba(30, 144, 255, 1)",
						pointHoverBackgroundColor: "rgba(30, 144, 255, 1)",
						pointHoverBorderColor: "rgba(30, 144, 255, 1)",
						data: humidityData
					}
				]
			};

			var chartPressure = {
				labels: timeData,
				datasets: [
					{
						fill: true,
						lineTension: 0.1,
						backgroundColor: "rgba(255, 71, 87, 0.75)",
						borderColor: "rgba(255, 71, 87, 1)",
						pointHoverBackgroundColor: "rgba(255, 71, 87, 1)",
						pointHoverBorderColor: "rgba(255, 71, 87, 1)",
						data: pressureData
					}
				]
			};

			var chartUv = {
				labels: timeData,
				datasets: [
					{
						fill: true,
						lineTension: 0.1,
						backgroundColor: "rgba(255, 165, 2, 0.75)",
						borderColor: "rgba(255, 165, 2, 1)",
						pointHoverBackgroundColor: "rgba(255, 165, 2, 1)",
						pointHoverBorderColor: "rgba(255, 165, 2, 1)",
						data: uvData
					}
				]
			};

			var chartAirquality = {
				labels: timeData,
				datasets: [
					{
						fill: true,
						lineTension: 0.1,
						backgroundColor: "rgba(39, 174, 96, 0.75)",
						borderColor: "rgba(39, 174, 96, 1)",
						pointHoverBackgroundColor: "rgba(39, 174, 96, 1)",
						pointHoverBorderColor: "rgba(39, 174, 96, 1)",
						data: airqualityData
					}
				]
			};			

			options = {
				legend: {
					display: false
				},
				tooltips: {
					displayColors: false
				},
				scales: {
     				xAxes: [{ 
                		ticks: {
                			fontColor: "rgba(0, 0, 0, 1)"
                		}
            		}],
            		yAxes: [{
                		ticks: {
                  			fontColor: "rgba(0, 0, 0, 1)"
                		}
            		}],
        		}
			}

			var ctx = $("#temperature");
			var temperatureChart = new Chart(ctx, {
				type: 'line',
				data: chartTemperature,
				options: options
			});

			var ctx = $("#humidity");
			var humidityChart = new Chart(ctx, {
				type: 'line',
				data: chartHumidity,
				options: options
			});

			var ctx = $("#pressure");
			var pressureChart = new Chart(ctx, {
				type: 'line',
				data: chartPressure,
				options: options
			});

			var ctx = $("#uv");
			var uvChart = new Chart(ctx, {
				type: 'line',
				data: chartUv,
				options: options
			});

			var ctx = $("#airquality");
			var uvChart = new Chart(ctx, {
				type: 'line',
				data: chartAirquality,
				options: options
			});							
		},
		error : function(data) {
		}
	});
});