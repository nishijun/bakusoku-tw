@extends('layouts.dashboard')
@section('title', 'ダッシュボード')
@section('content')

<!-- グラフ-->
<!-- PC版 -->
<div class="graphs-pc">

  <!-- フォロワー -->
  <div class="graph">
    <div class="graph-info">
      <p class="graph-info-title">フォロワー</p>
      <p class="graph-info-number">{{ $user->follower }}</p>
    </div>
    <div class="graph-figure">
      @if ($analyticsData)
      <canvas id="follower-pc" class="chart"></canvas>
      @else
      <p class="nodata">データは明日以降表示されます</p>
      @endif
    </div>
  </div>

  <!-- フォロー -->
  <div class="graph">
    <div class="graph-info">
      <p class="graph-info-title">フォロー</p>
      <p class="graph-info-number">{{ $user->follow }}</p>
    </div>
    <div class="graph-figure">
      @if ($analyticsData)
      <canvas id="follow-pc" class="chart"></canvas>
      @else
      <p class="nodata">データは明日以降表示されます</p>
      @endif
    </div>
  </div>

  <!-- 新規フォロワー -->
  <div class="graph">
    <div class="graph-info">
      <p class="graph-info-title">新規フォロワー</p>
      <p class="graph-info-number">
      @if ($analyticsData)
        {{ end($analyticsData[4]) }}
      @else
        0
      @endif
      </p>
    </div>
    <div class="graph-figure">
      @if ($analyticsData)
      <canvas id="new-follower-pc" class="chart"></canvas>
      @else
      <p class="nodata">データは明日以降表示されます</p>
      @endif
    </div>
  </div>

  <!-- 新規フォロー -->
  <div class="graph">
    <div class="graph-info">
      <p class="graph-info-title">新規フォロー</p>
      <p class="graph-info-number">
      @if ($analyticsData)
        {{ end($analyticsData[3]) }}
      @else
        0
      @endif
      </p>
    </div>
    <div class="graph-figure">
      @if ($analyticsData)
      <canvas id="new-follow-pc" class="chart"></canvas>
      @else
      <p class="nodata">データは明日以降表示されます</p>
      @endif
    </div>
  </div>

</div>

<!-- SP版（Swiper） -->
<div class="graphs-sp">
  <div class="swiper-container swiper-dashboard sp">
    <div class="swiper-wrapper">

      <!-- フォロワー -->
      <div class="swiper-slide extra">
        <div class="graph">
          <div class="graph-info">
            <p class="graph-info-title">フォロワー</p>
            <p class="graph-info-number">{{ $user->follower }}</p>
          </div>
          <div class="graph-figure">
            @if ($analyticsData)
            <canvas id="follower-sp" class="chart"></canvas>
            @else
            <p class="nodata">データは明日以降表示されます</p>
            @endif
          </div>
        </div>

        <!-- フォロー -->
        <div class="graph">
          <div class="graph-info">
            <p class="graph-info-title">フォロー</p>
            <p class="graph-info-number">{{ $user->follow }}</p>
          </div>
          <div class="graph-figure">
            @if ($analyticsData)
            <canvas id="follow-sp" class="chart"></canvas>
            @else
            <p class="nodata">データは明日以降表示されます</p>
            @endif
          </div>
        </div>
      </div>

      <!-- 新規フォロワー -->
      <div class="swiper-slide">
        <div class="graph">
          <div class="graph-info">
            <p class="graph-info-title">新規フォロワー</p>
            <p class="graph-info-number">
            @if ($analyticsData)
              {{ end($analyticsData[4]) }}
            @else
              0
            @endif
            </p>
          </div>
          <div class="graph-figure">
            @if ($analyticsData)
            <canvas id="new-follower-sp" class="chart"></canvas>
            @else
            <p class="nodata">データは明日以降表示されます</p>
            @endif
          </div>
        </div>

        <!-- 新規フォロー -->
        <div class="graph">
          <div class="graph-info">
            <p class="graph-info-title">新規フォロー</p>
            <p class="graph-info-number">
            @if ($analyticsData)
              {{ end($analyticsData[3]) }}
            @else
              0
            @endif
            </p>
          </div>
          <div class="graph-figure">
            @if ($analyticsData)
            <canvas id="new-follow-sp" class="chart"></canvas>
            @else
            <p class="nodata">データは明日以降表示されます</p>
            @endif
          </div>
        </div>
      </div>

    </div>
    <div class="swiper-pagination"></div>
  </div>
</div>

@endsection
