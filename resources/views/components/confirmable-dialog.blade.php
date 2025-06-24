@props(['title', 'affirmText', 'denyText'])

@vite('resources/css/components/confirmable-dialog.css')
<dialog class="confirmable-dialog">
    <h3 class="confirmable-dialog__title">{{ $title }}</h3>
    <div class="confirmable-content">
        <h4>Are you sure you want to delete this item?</h4>
        <p>This action is irreversible</p>
        <button type="submit" class="affirm-button" form="$formId">{{ $affirmText }}</button>
        <button class="deny-button" type="button">{{ $denyText }}</button>
    </div>
</dialog>
