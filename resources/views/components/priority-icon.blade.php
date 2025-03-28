<div class="flex items-center">
    @if ($priority === 'Trivial')
        <svg width="20" height="20" viewBox="0 0 24 24">
            <circle cx="12" cy="13" r="8" stroke="gray" stroke-width="2" fill="none" />
        </svg>
    @elseif ($priority === 'Lowest')
        <svg width="20" height="20" viewBox="0 0 24 24">
            <polyline points="5,4 12,11 19,4" stroke="blue" stroke-width="2" fill="none" />
            <polyline points="5,9 12,16 19,9" stroke="blue" stroke-width="2" fill="none" />
            <polyline points="5,14 12,21 19,14" stroke="blue" stroke-width="2" fill="none" />
        </svg>
    @elseif ($priority === 'Lower')
        <svg width="20" height="20" viewBox="0 0 24 24">
            <polyline points="5,6 12,13 19,6" stroke="blue" stroke-width="2" fill="none" />
            <polyline points="5,12 12,19 19,12" stroke="blue" stroke-width="2" fill="none" />
        </svg>
    @elseif ($priority === 'Low')
        <svg width="20" height="20" viewBox="0 0 24 24">
            <polyline points="5,11 12,18 19,11" stroke="blue" stroke-width="2" fill="none" />
        </svg>
    @elseif ($priority === 'Minor')
        <svg width="20" height="20" viewBox="0 0 24 24">
            <line x1="5" y1="11" x2="19" y2="11" stroke="goldenrod" stroke-width="2" />
            <line x1="5" y1="15" x2="19" y2="15" stroke="goldenrod" stroke-width="2" />
        </svg>
    @elseif ($priority === 'High')
        <svg width="20" height="20" viewBox="0 0 24 24">
            <polyline points="5,18 12,11 19,18" stroke="red" stroke-width="2" fill="none" />
        </svg>
    @elseif ($priority === 'Higher')
        <svg width="20" height="20" viewBox="0 0 24 24">
            <polyline points="5,18 12,11 19,18" stroke="red" stroke-width="2" fill="none" />
            <polyline points="5,12 12,5 19,12" stroke="red" stroke-width="2" fill="none" />
        </svg>
    @elseif ($priority === 'Highest')
        <svg width="20" height="20" viewBox="0 0 24 24">
            <polyline points="5,19 12,12 19,19" stroke="red" stroke-width="2" fill="none" />
            <polyline points="5,13 12,6 19,13" stroke="red" stroke-width="2" fill="none" />
            <polyline points="5,7 12,1 19,7" stroke="red" stroke-width="2" fill="none" />
        </svg>
    @elseif ($priority === 'Critical')
        <svg width="20" height="20" viewBox="0 0 24 24">
            <polygon points="5,6 19,6 19,19 12,23 5,19" fill="red" />
        </svg>
    @elseif ($priority === 'Blocker')
        <svg width="20" height="20" viewBox="0 0 24 24">
            <circle cx="12" cy="13" r="8" fill="red" />
            <line x1="7" y1="13" x2="17" y2="13" stroke="white" stroke-width="2" />
        </svg>
    @endif
</div>
