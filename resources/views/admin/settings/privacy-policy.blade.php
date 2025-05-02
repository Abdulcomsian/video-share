@extends('layouts/contentNavbarLayout')

@section('title', 'Privacy Policy')
@push('my-styles')

    <link rel="stylesheet" href="{{ asset('assets/libraries/parsely/parsley.css') }}" />

    <link href="{{ asset('assets/libraries/quill/quill.snow.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/libraries/quill/quill-custom.css') }}" rel="stylesheet" type="text/css" />

@endpush
@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Settings /</span> Privacy Policy
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('admin:settings.save.content') }}" method="post" id="savePrivacyPolicy"
                        data-parsley-validate>
                        @csrf
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <label for="points" class="form-label">Content</label>
                                <div id="quill-editor" class="bg-white "></div>
                                <textarea class="d-none" id="privacyPolicyContent" name="content" required>{{ old('content', $privacyPolicy->content ?? '') }}</textarea>
                                <input type="hidden" name="slug" value="{{ $privacyPolicySlug }}" />

                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('my-script')
    <script src="{{ asset('assets/libraries/parsely/parsley.min.js') }}"></script>
    <script src="{{ asset('assets/libraries/quill/quill.js') }}"></script>
    <script>

        $('#savePrivacyPolicy').parsley()

        var quill = new Quill('#quill-editor', {
            theme: 'snow'
        });
        // Your content in HTML format (retrieved from the backend)
        let savedContent = `{!! old('content', $privacyPolicy->content ?? '') !!}`;
        // Load the saved content into the editor
        quill.clipboard.dangerouslyPasteHTML(savedContent);
        quill.on('text-change', function() {
            document.querySelector('#privacyPolicyContent').value = quill.root.innerHTML;
        });

    </script>
@endpush
