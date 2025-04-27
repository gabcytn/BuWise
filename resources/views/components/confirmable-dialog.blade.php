@props(['title', 'affirmText', 'denyText'])

@vite('resources/css/components/confirmable-dialog.css')
<dialog class="confirmable-dialog">
    <h3 class="confirmable-dialog__title">{{ $title }}</h3>
    <div class="buttons">
        <button type="submit" class="affirm-button" form="$formId">{{ $affirmText }}</button>
        <button class="deny-button" type="button">{{ $denyText }}</button>
    </div>
</dialog>
