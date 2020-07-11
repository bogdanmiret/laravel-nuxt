@if($restaurant->package && $restaurant->package->id < 4 && !isset($_COOKIE[$restaurant->id."_upgrade_notice"]))
<div class="alert alert-icon alert-upgrade alert-dismissible alert-success">
        <button onclick="closeUpgradeNotice({{ $restaurant->id }})" type="button" class="close" data-dismiss="alert" aria-label="Close">
                <i class="fa fa-times" aria-hidden="true"></i>
         </button>

       <a href="javascript:void(0)" class="text-white hover:text-white text-xl" onclick='company_session({{ $restaurant->id}})'>{!! trans('business.upgrade_notice', ['package' => $restaurant->package->name, 'restaurant_id' =>  $restaurant->id]) !!}</a>  
</div>
@endif

@push('scripts')
<script>
    function closeUpgradeNotice(id) {
        setCookie(id+"_upgrade_notice", true, 7);
    }

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
</script>
@endpush