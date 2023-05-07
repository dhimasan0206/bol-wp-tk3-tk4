@extends('adminlte::page')

@section('title', 'Order Report Dashboard')

@section('content_header')
    <h1>Order Report Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col">
        <h3>All</h3>
        <canvas id="all"></canvas>
    </div>
    <div class="col">
        <h3>Daily</h3>
        <canvas id="daily"></canvas>
    </div>
    <div class="col">
        <h3>Weekly</h3>
        <canvas id="weekly"></canvas>
    </div>
</div>
<div class="row">
    <div class="col">
        <h3>Monthly</h3>
        <canvas id="monthly"></canvas>
    </div>
    <div class="col">
        <h3>Quarterly</h3>
        <canvas id="quarterly"></canvas>
    </div>
    <div class="col">
        <h3>Yearly</h3>
        <canvas id="yearly"></canvas>
    </div>
</div>
@stop

@section('css')
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
    var allStatus =  {{Js::from($all_status)}};
    var allCount =  {{Js::from($all_count)}};
    const allData = {
        labels: allStatus,
        datasets: [{
            label: 'All Order',
            data: allCount,
        }]
    };
    const allCfg = {
        type: 'pie',
        data: allData,
        options: {}
    };

    const all = new Chart(
        document.getElementById('all'),
        allCfg
    );

    var dailyStatus =  {{Js::from($daily_status)}};
    var dailyCount =  {{Js::from($daily_count)}};
    const dailyData = {
        labels: dailyStatus,
        datasets: [{
            label: 'Daily Order',
            data: dailyCount,
        }]
    };
    const dailyCfg = {
        type: 'pie',
        data: dailyData,
        options: {}
    };

    const daily = new Chart(
        document.getElementById('daily'),
        dailyCfg
    );

    var weeklyStatus =  {{Js::from($weekly_status)}};
    var weeklyCount =  {{Js::from($weekly_count)}};
    const weeklyData = {
        labels: weeklyStatus,
        datasets: [{
            label: 'Weekly Order',
            data: weeklyCount,
        }]
    };
    const weeklyCfg = {
        type: 'pie',
        data: weeklyData,
        options: {}
    };

    const weekly = new Chart(
        document.getElementById('weekly'),
        weeklyCfg
    );

    var monthlyStatus =  {{Js::from($monthly_status)}};
    var monthlyCount =  {{Js::from($monthly_count)}};
    const monthlyData = {
        labels: monthlyStatus,
        datasets: [{
            label: 'Monthly Order',
            data: monthlyCount,
        }]
    };
    const monthlyCfg = {
        type: 'pie',
        data: monthlyData,
        options: {}
    };

    const monthly = new Chart(
        document.getElementById('monthly'),
        monthlyCfg
    );

    var quarterlyStatus =  {{Js::from($quarterly_status)}};
    var quarterlyCount =  {{Js::from($quarterly_count)}};
    const quarterlyData = {
        labels: quarterlyStatus,
        datasets: [{
            label: 'Quarterly Order',
            data: quarterlyCount,
        }]
    };
    const quarterlyCfg = {
        type: 'pie',
        data: quarterlyData,
        options: {}
    };

    const quarterly = new Chart(
        document.getElementById('quarterly'),
        quarterlyCfg
    );

    var yearlyStatus =  {{Js::from($yearly_status)}};
    var yearlyCount =  {{Js::from($yearly_count)}};
    const yearlyData = {
        labels: yearlyStatus,
        datasets: [{
            label: 'Quarterly Order',
            data: yearlyCount,
        }]
    };
    const yearlyCfg = {
        type: 'pie',
        data: yearlyData,
        options: {}
    };

    const yearly = new Chart(
        document.getElementById('yearly'),
        yearlyCfg
    );
</script>
@stop
