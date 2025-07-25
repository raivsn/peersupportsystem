@foreach($replies as $i => $reply)
<div class="reply-item-wrapper" style="margin-left: {{ $level == 1 ? 32 : 0 }}px;">
    @if($level == 1)
        <div class="reply-indent" style="height: 100%;"></div>
    @endif
    <div class="reply-item{{ $level == 1 ? ' level-1' : '' }}">
        <div class="reply-header" style="position: relative;">
            <div class="reply-header-content">
                <span class="reply-author">
                <span style="color:#5a5ad1; font-weight:bold;">{{ $reply->user_name }}</span>
                    <span style="color:#222; font-size:0.95em; font-weight:400;">({{ ($reply->user_role ?? '') === 'admin' ? 'Administrator' : ($reply->caregiver_status ?? 'Unknown Role') }})</span>
                    @if($userId == $reply->user_id)<span class="reply-you">(You)</span>@endif
                </span>
                <span class="reply-meta">{{ \Carbon\Carbon::parse($reply->created_at)->diffForHumans() }}</span>
            </div>
            @if(($userId == $reply->user_id) || (isset($role) && $role === 'admin'))
                <form method="POST" action="{{ route('forum.reply.delete', ['replyId' => $reply->id]) }}" class="delete-reply-form" onsubmit="return confirmDeleteReply(event)">
                    @csrf
                    <button type="submit" class="delete-reply-btn">Delete</button>
                </form>
            @endif
        </div>
        <div class="reply-content">{{ $reply->body }}</div>
        <form id="reply-form-inline-{{ $reply->id }}" class="reply-form-inline" method="POST" action="{{ route('forum.reply.add', ['postId' => $postId]) }}">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $reply->id }}" />
            @if($errors->has('badwords') && session('reply_parent_id') == $reply->id)
                <div style="color: #e74c3c; margin-bottom: 0.5rem; font-weight: bold;">{{ $errors->first('badwords') }}</div>
            @endif
            <input type="text" name="body" placeholder="Reply to this comment..." required value="{{ session('reply_parent_id') == $reply->id ? old('body') : '' }}" />
            <button type="submit">Reply</button>
        </form>
        @if(!empty($reply->children))
            @if($level == 0)
                {{-- Show first-level replies directly --}}
                @include('forum.partials.reply-tree', ['replies' => $reply->children, 'postId' => $postId, 'userId' => $userId, 'level' => $level + 1, 'role' => $role ?? null])
            @else
                {{-- Hide deeper levels behind dropdown --}}
                <div class="nested-replies" style="display: none;">
                    @include('forum.partials.reply-tree', ['replies' => $reply->children, 'postId' => $postId, 'userId' => $userId, 'level' => $level + 1, 'role' => $role ?? null])
                </div>
                <div style="margin-top: 0.7rem;">
                    <button class="expand-replies-btn" data-count="{{ count($reply->children) }}" onclick="toggleNestedReplies(this)" style="background: none; border: none; color: #5a5ad1; cursor: pointer; font-size: 0.9rem;">
                        ▼ Show replies ({{ count($reply->children) }})
                    </button>
                </div>
            @endif
        @endif
    </div>
</div>
@endforeach

<style>
.delete-reply-form { display: none; position: absolute; right: 0; top: 0; }
.reply-item:hover .delete-reply-form { display: inline; }
.delete-reply-btn { background:#e74c3c; color:#fff; border:none; border-radius:4px; padding:0.2rem 0.8rem; font-size:0.95rem; cursor:pointer; }
</style>
<script>
function toggleNestedReplies(btn) {
    var nestedReplies = btn.parentElement.previousElementSibling;
    var count = btn.getAttribute('data-count');
    if (nestedReplies.style.display === 'none') {
        nestedReplies.style.display = 'block';
        btn.innerHTML = '▲ Hide replies';
    } else {
        nestedReplies.style.display = 'none';
        btn.innerHTML = '▼ Show replies (' + count + ')';
    }
}
function confirmDeleteReply(event) {
    if (!confirm('Are you sure you want to delete this reply and all its child replies?')) {
        event.preventDefault();
        return false;
    }
    return true;
}
</script> 