@if(isset($seoData->faq_schema) && is_array($seoData->faq_schema) && count($seoData->faq_schema) > 0)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
    @foreach($seoData->faq_schema as $index => $faq)
    {
        "@type": "Question",
        "name": "{{ $faq['question'] ?? '' }}",
        "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ $faq['answer'] ?? '' }}"
        }
    }@if(!$loop->last),@endif 
    @endforeach
    ]
}
</script>
@endif
