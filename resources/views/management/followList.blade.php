@extends('layouts.management')
@section('title', 'フォローリスト')
@section('content')
<div class="outer">

  <!-- ドロップダウンメニュー -->
  <div class="drop-menus">

    <!-- ソートメニュー -->
    <div class="drop-menu drop-menu1">
      <p class="drop-menu-item drop-menu-display js-click-menu-toggle1">
        <span class="js-click-menu-display1">
        @switch ($sort)
          @case ('follow_big')
            フォロー多い順
            @break
          @case ('follow_small')
            フォロー少ない順
            @break
          @case ('follower_big')
            フォロワー多い順
            @break
          @case ('follower_small')
            フォロワー少ない順
            @break
          @case ('tweet_big')
            ツイート多い順
            @break
          @case ('tweet_small')
            ツイート少ない順
            @break
        @endswitch
        </span>
        <i class="fas fa-chevron-down"></i>
      </p>
      <ul class="drop-menu-list js-click-menu1">
        <li class="drop-menu-item drop-menu-none js-click-menu-item1">
          <a href="{{route('management.followList', ['id' => $user->twitter_id, 'order' => $order, 'sort' => 'follow_big'])}}">フォロー多い順</a>
        </li>
        <li class="drop-menu-item drop-menu-none js-click-menu-item1">
          <a href="{{route('management.followList', ['id' => $user->twitter_id, 'order' => $order, 'sort' => 'follow_small'])}}">フォロー少ない順</a>
        </li>
        <li class="drop-menu-item drop-menu-none js-click-menu-item1">
          <a href="{{route('management.followList', ['id' => $user->twitter_id, 'order' => $order, 'sort' => 'follower_big'])}}">フォロワー多い順</a>
        </li>
        <li class="drop-menu-item drop-menu-none js-click-menu-item1">
          <a href="{{route('management.followList', ['id' => $user->twitter_id, 'order' => $order, 'sort' => 'follower_small'])}}">フォロワー少ない順</a>
        </li>
        <li class="drop-menu-item drop-menu-none js-click-menu-item1">
          <a href="{{route('management.followList', ['id' => $user->twitter_id, 'order' => $order, 'sort' => 'tweet_big'])}}">ツイート多い順</a>
        </li>
        <li class="drop-menu-item drop-menu-none js-click-menu-item1">
          <a href="{{route('management.followList', ['id' => $user->twitter_id, 'order' => $order, 'sort' => 'tweet_small'])}}">ツイート少ない順</a>
        </li>
      </ul>
    </div>

    <!-- フィルターメニュー -->
    <div class="drop-menu drop-menu2">
      <p class="drop-menu-item drop-menu-display js-click-menu-toggle2">
        フィルタ
        <i class="fas fa-chevron-down"></i>
      </p>
      <form action="{{route('management.request', ['id' => $user->twitter_id, 'order' => $order, 'sort' => $sort])}}" method="post" class="js-filter-form">
        @csrf
        <ul class="drop-menu-list js-click-menu2">
          @if ($order === 'follow')
            <li class="drop-menu-item js-click-checkbox-target @if (array_search('フォロワーのみ', (array)$filter) !== false) active @endif">
              <input type="checkbox" class="checkbox js-click-checkbox" id="filter1" name="filter[]" value="フォロワーのみ" @if (array_search('フォロワーのみ', (array)$filter) !== false) checked @endif>
              <label class="checkbox-label drop-menu-item-label" for="filter1">フォロワーのみ</label>
            </li>
          @elseif ($order === 'follower')
            <li class="drop-menu-item js-click-checkbox-target @if (array_search('フォローのみ', (array)$filter) !== false) active @endif">
              <input type="checkbox" class="checkbox js-click-checkbox" id="filter2" name="filter[]" value="フォローのみ" @if (array_search('フォローのみ', (array)$filter) !== false) checked @endif>
              <label class="checkbox-label drop-menu-item-label" for="filter2">フォローのみ</label>
            </li>
          @endif
          <li class="drop-menu-item js-click-checkbox-target @if (array_search('フォロワーが1000人以上', (array)$filter) !== false) active @endif">
            <input type="checkbox" class="checkbox js-click-checkbox" id="filter3" name="filter[]" value="フォロワーが1000人以上" @if (array_search('フォロワーが1000人以上', (array)$filter) !== false) checked @endif>
            <label class="checkbox-label drop-menu-item-label" for="filter3">フォロワーが1000人以上</label>
          </li>
          <li class="drop-menu-item js-click-checkbox-target @if (array_search('フォローが1000人以上', (array)$filter) !== false) active @endif">
            <input type="checkbox" class="checkbox js-click-checkbox" id="filter4" name="filter[]" value="フォローが1000人以上" @if (array_search('フォローが1000人以上', (array)$filter) !== false) checked @endif>
            <label class="checkbox-label drop-menu-item-label" for="filter4">フォローが1000人以上</label>
          </li>
        </ul>
      </form>
    </div>
  </div>
  <div class="filter-area">
    <i class="fas fa-filter"></i>
    <p class="filter-area-paragraph js-click-checkbox-display">
    @if (!empty($filter))
      @foreach ($filter as $item)
        {{ $item }}
      @endforeach
    @else
      フィルターなし
    @endif
    </p>
  </div>

  <div class="list-management">
    <a href="{{route('management.followList', ['id' => $user->twitter_id, 'order' => 'follow', 'sort' => $sort])}}" class="list-management-link @if ($order === 'follow') active @endif">フォロー</a>
    <a href="{{route('management.followList', ['id' => $user->twitter_id, 'order' => 'follower', 'sort' => $sort])}}" class="list-management-link @if ($order === 'follower') active @endif">フォロワー</a>
  </div>

  <!-- フォローリスト -->
  <div class="lists">

    <!-- PC版 -->
    @if (!$followLists)
      <p class="noshow noshow-pc">表示するデータがありません</p>
    @else
      @foreach ($followLists as $followList)
      <div class="list-pc">
        <div class="list-pc-info">
          <img src="{{ $followList->profile_image_url_https }}" alt="User image" class="user-img">
          <div class="list-pc-info-user">
            <div class="list-common-user-info">
              <span class="list-common-user-name">{{ $followList->name }}</span>
              <span class="list-common-user-id">{{ '@' . $followList->screen_name }}</span>
            </div>
            <p class="list-common-user-sentence">{{ $followList->description }}</p>
            <ul class="list-pc-info-user-list">
              <li class="list-pc-info-user-list-item">
                <span class="list-pc-info-user-list-item-span">フォロー</span>
                {{ $followList->friends_count }}
              </li>
              <li class="list-pc-info-user-list-item">
                <span class="list-pc-info-user-list-item-span">フォロワー</span>
                {{ $followList->followers_count }}
              </li>
              <li class="list-pc-info-user-list-item">
                <span class="list-pc-info-user-list-item-span">ツイート</span>
                {{ $followList->statuses_count }}
              </li>
            </ul>
          </div>
        </div>
        <div class="list-pc-buttons">
          <div class="list-pc-button">
            <form action="{{ route('management.request', ['id' => $user->twitter_id, 'order' => $order, 'sort' => $sort])}}" method="post">
              @csrf
              <input type="hidden" value="{{ $followList->id }}" name="target">
              @if ($followList->following)
                <input type="hidden" value="unfollow" name="option">
                <button class="list-common-remove btn" type="submit">
                  <i class="fas fa-user-minus"></i>
                  解除
                </button>
              @else
                <input type="hidden" value="follow" name="option">
                <button class="list-common-follow btn" type="submit">
                  <i class="fas fa-user-plus"></i>
                  フォロー
                </button>
              @endif
            </form>
            @if ($order === 'follower' || $followList->followed_by)
              <div class="list-common-follower">フォロワーです</div>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    @endif

    <!-- SP版 -->
    @if (!$followLists)
      <p class="noshow noshow-sp">表示するデータがありません</p>
    @else
      @foreach ($followLists as $followList)
      <div class="list-sp">
        <div class="list-sp-theme">
          <div class="list-sp-theme-user">
            <img src="{{ $followList->profile_image_url_https }}" alt="User image" class="user-img">
            <div class="list-sp-theme-user-info">
              <p class="list-common-user-name">{{ $followList->name }}</p>
              <p class="list-common-user-id">{{ '@' . $followList->screen_name }}</p>
            </div>
          </div>
        </div>
        <p class="list-common-user-sentence">{{ $followList->description }}</p>
        <ul class="list-common-list">
          <li class="list-common-list-item">
            <span class="list-common-list-item-span">フォロー</span>
            {{ $followList->friends_count }}
          </li>
          <li class="list-common-list-item">
            <span class="list-common-list-item-span">フォロワー</span>
            {{ $followList->followers_count }}
          </li>
          <li class="list-common-list-item">
            <span class="list-common-list-item-span">ツイート</span>
            {{ $followList->statuses_count }}
          </li>
        </ul>
        <div class="list-sp-buttons">
          <form action="{{ route('management.request', ['id' => $user->twitter_id, 'order' => $order, 'sort' => $sort])}}" method="post">
            @csrf
            <input type="hidden" value="{{ $followList->id }}" name="target">
            @if ($followList->following)
              <input type="hidden" value="unfollow" name="option">
              <button class="list-common-remove btn" type="submit">
                <i class="fas fa-user-minus"></i>
                解除
              </button>
            @else
              <input type="hidden" value="follow" name="option">
              <button class="list-common-follow btn" type="submit">
                <i class="fas fa-user-plus"></i>
                フォロー
              </button>
            @endif
          </form>
          @if ($order === 'follower' || $followList->followed_by)
            <div class="list-common-follower">フォロワーです</div>
          @endif
        </div>
      </div>
      @endforeach
    @endif
  </div>
</div>
@endsection
