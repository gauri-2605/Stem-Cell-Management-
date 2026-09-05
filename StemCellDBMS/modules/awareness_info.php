<?php
// Awareness — More Information page (new)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Awareness — Stem Cells</title>
  <style>
    body { font-family: Arial,Helvetica,sans-serif; line-height:1.6; margin:0; padding:0; background:#f7f7f7; }
    header { background:#0066cc; color:#fff; padding:18px; text-align:center }
    main { max-width:1000px; margin:24px auto; padding:18px; background:#fff; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.06);} 
    h1,h2 { color:#003366 }
    .grid { display:grid; grid-template-columns: 1fr 320px; gap:20px; }
    .card { padding:12px; border-radius:6px; background:#fbfbfb; border:1px solid #eee }
    .media img { max-width:100%; border-radius:6px }
    .video { position:relative; padding-bottom:56.25%; height:0; overflow:hidden }
    .video iframe { position:absolute; top:0; left:0; width:100%; height:100% }
    .back { margin-bottom:12px }
    ul { margin-left:1rem }
    @media (max-width:800px){ .grid{grid-template-columns:1fr} }
  </style>
</head>
<body>
  <header>
    <h1>More Information — Stem Cells Awareness</h1>
    <p style="margin:6px 0 0;">Reliable basics, therapy options, safety guidelines and awareness media</p>
  </header>
  <main>
    <div class="back"><a href="/StemCellDBMS/modules/chatbot.php">&larr; Back to Chatbot</a> | <a href="/StemCellDBMS/home.php">Home</a></div>

    <div class="grid">
      <section>
        <article class="card">
          <h2>What are stem cells?</h2>
          <p>Stem cells are undifferentiated cells with the ability to divide and develop into specialised cell types. They are used to study human development, investigate disease mechanisms and develop new treatments.</p>
          <ul>
            <li>Self-renewal: they can divide to produce more stem cells.</li>
            <li>Potency: they can give rise to different cell types (multipotent, pluripotent).</li>
          </ul>
        </article>

        <article class="card" style="margin-top:16px">
          <h2>Types of stem cells</h2>
          <p>Common categories:</p>
          <ul>
            <li><strong>Embryonic stem cells</strong> — pluripotent cells from early embryos that can form almost any cell type.</li>
            <li><strong>Adult (somatic) stem cells</strong> — found in tissues (e.g., bone marrow) and typically generate a narrower range of cell types.</li>
            <li><strong>Induced pluripotent stem cells (iPSCs)</strong> — adult cells reprogrammed to a pluripotent state in the lab.</li>
          </ul>
        </article>

        <article class="card" style="margin-top:16px">
          <h2>Therapy options</h2>
          <p>Stem cell therapies are an active research area. Examples of existing and developing approaches:</p>
          <ul>
            <li><strong>Bone marrow / hematopoietic stem cell transplant</strong> — a well-established treatment for blood cancers like leukemia.</li>
            <li><strong>Cell replacement therapies</strong> — experimental therapies for neurodegenerative diseases, diabetes, and more.</li>
            <li><strong>Tissue engineering</strong> — combining stem cells with scaffolds to repair damaged tissues.</li>
          </ul>
          <p>Note: many proposed therapies are experimental — consult clinical trials and medical professionals before pursuing treatment.</p>
        </article>

        <article class="card" style="margin-top:16px">
          <h2>Safety guidelines</h2>
          <ul>
            <li>Only consider treatments approved by recognized health authorities (e.g., national regulatory agencies).</li>
            <li>Beware of clinics offering unproven stem cell “cures”.</li>
            <li>Ask for evidence: clinical trial results, peer-reviewed studies, and qualified physician oversight.</li>
            <li>Understand risks and obtain informed consent before participating in trials.</li>
          </ul>
        </article>
      </section>

      <aside>
        <div class="card media">
          <h3>Awareness media</h3>
          <div id="playerWrap">
            <div id="playerMain" class="video">
              <iframe id="mainPlayerA" src="https://www.youtube.com/embed/evH0I7Coc54" frameborder="0" allowfullscreen></iframe>
            </div>
            <div id="thumbs" style="margin-top:10px;display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:8px"></div>
          </div>
          <script>
            (function(){
              const vids = ['evH0I7Coc54','MQXZ7cGZo1w','3KDsB7Rjaaw','Rlbx0ZpJxsU','oy0RPZRLT8g','thXCxztTP60','lmPqdWnirVY'];
              const thumbs = document.getElementById('thumbs');
              const main = document.getElementById('mainPlayerA');
              vids.forEach((id, i)=>{
                const d = document.createElement('div');
                d.style.cursor='pointer';
                d.innerHTML = `<img src="https://img.youtube.com/vi/${id}/mqdefault.jpg" style="width:100%;border-radius:4px"><div style="font-size:13px;margin-top:6px">${i+1}. Video</div>`;
                d.addEventListener('click', ()=>{ 
                  main.src = 'https://www.youtube.com/embed/'+id+'?rel=0'; 
                  Array.from(thumbs.children).forEach(c=>c.style.boxShadow='none');
                  d.style.boxShadow='0 2px 8px rgba(0,0,0,0.12)';
                });
                thumbs.appendChild(d);
              });
              // highlight first
              if (thumbs.children.length>0) { thumbs.children[0].style.boxShadow='0 2px 8px rgba(0,0,0,0.12)'; }
            })();
          </script>
        </div>

        <!-- removed duplicate empty video blocks -->
        <div class="card" style="margin-top:16px">
          <h3>Quick links</h3>
          <ul>
            <li><a href="https://www.who.int/" target="_blank">World Health Organization</a></li>
            <li><a href="https://clinicaltrials.gov/" target="_blank">ClinicalTrials.gov</a></li>
            <li><a href="https://www.ncbi.nlm.nih.gov/" target="_blank">PubMed / NCBI</a></li>
          </ul>
        </div>
      </aside>
    </div>

    <div style="margin-top:18px">Want this content expanded? Contact the site admin or ask the chatbot for more details.</div>

  </main>
</body>
</html>