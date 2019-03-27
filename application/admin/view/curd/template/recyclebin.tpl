{extend name="[MODULE]template/base" /}
{block name="content"}
<div class="page-container">
    {include file='form' /}
    <div class="cl pd-5 bg-1 bk-gray">
        <span class="l">
            {tp:menu menu='recycle,deleteForever,clear' /}
        </span>
        <span class="r pt-5 pr-5">
            共有数据 ：<strong>{$count ?? '0'}</strong> 条
        </span>
    </div>
    <table class="table table-border table-bordered table-hover table-bg mt-20">
        <thead>
        <tr class="text-c">
            {include file="th" /}
            <th width="70">操作</th>
        </tr>
        </thead>
        <tbody>
        {volist name="list" id="vo"}
        <tr class="text-c">
            {include file="td" /}
            <td class="f-14">
                {tp:menu menu='srecycle' /}
                {tp:menu menu='sdeleteforever' /}
            </td>
        </tr>
        {/volist}
        </tbody>
    </table>
    <div class="page-bootstrap">{$page ?? ''}</div>
</div>
{/block}
[SCRIPT]
