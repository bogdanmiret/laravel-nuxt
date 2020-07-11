@if (session('status'))
	{{--{{ dd(session('status')) }}--}}
	@push('scripts')

		@php
			if(isset(session('status')['code'])){
				$status = session('status')['code'];
			} else {
				$status = 'info';
			}

			if(!isset(session('status')['message']) && !isset(session('status')['message']) && !is_array(session('status'))){
				$message  = session('status');
			} elseif (isset(session('status')['message'])){
				$message = session('status')['message'];
			} else {
				$message = '';
			}
		@endphp


		<script>
            $(document).ready(function () {

                iziToast.{{ $status }}({
                    title: '{!! $message !!}',
					{{--message: '{{ (session('status')['message']) }}'--}}
                });
            });
		</script>
	@endpush

	@if(!isset(session('status')['persist']))
		{{ session()->forget('status') }}
	@endif

@endif