<?php
// More Information module
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>More Information — Stem Cells</title>
  <style>
    body { font-family: Arial,Helvetica,sans-serif; background:#f4f7fb; margin:0 }
    header { background:#0b63b1; color:#fff; padding:16px; text-align:center }
    main { max-width:1000px; margin:22px auto; padding:18px; background:#fff; border-radius:6px }
    .section { margin-bottom:18px }
    .media { display:flex; gap:12px; flex-wrap:wrap }
    .media img{max-width:320px;border-radius:6px}
    .video{position:relative;padding-bottom:56.25%;height:0;overflow:hidden}
    .video iframe{position:absolute;left:0;top:0;width:100%;height:100%}
    a.card{display:inline-block;padding:10px 14px;background:#0066cc;color:#fff;border-radius:6px;text-decoration:none}
  </style>
</head>
<body>
  <header>
    <h1>More Information</h1>
    <p>Learn more about stem cells: types, therapies, safety and media resources.</p>
  </header>

  <main>
    <p><a class="card" href="/StemCellDBMS/modules/chatbot.php">Open Chatbot</a> <a class="card" href="/StemCellDBMS/home.php" style="background:#666;margin-left:8px">Back to Dashboard</a></p>

    <section class="section">
      <h2>What are stem cells?</h2>
      <p>Stem cells are undifferentiated cells capable of self-renewal and differentiation into specialised cells. They are central to developmental biology and regenerative medicine.</p>
    </section>

    <section class="section">
      <h2>Types of stem cells</h2>
      <ul>
        <li><strong>Embryonic stem cells</strong> — pluripotent cells from early embryos.</li>
        <li><strong>Adult stem cells</strong> — found in tissues, typically multipotent.</li>
        <li><strong>Induced pluripotent stem cells (iPSCs)</strong> — adult cells reprogrammed to a pluripotent state.</li>
      </ul>
    </section>

    <section class="section">
      <h2>Therapy options</h2>
      <p>Examples of applications and therapies:</p>
      <ul>
        <li>Bone marrow transplants for blood disorders.</li>
        <li>Investigational cell replacement therapies for neurodegenerative diseases.</li>
        <li>Tissue engineering and regenerative approaches.</li>
      </ul>
    </section>

    <section class="section">
      <h2>Safety guidelines</h2>
      <ul>
        <li>Only consider treatments supported by clinical evidence and regulated by authorities.</li>
        <li>Consult qualified medical professionals and ask for trial data.</li>
        <li>Understand risks and ensure informed consent.</li>
      </ul>
    </section>

    <section class="section">
      <h2>Videos & images for awareness</h2>
      <div>
        <div id="player" class="video" style="margin-bottom:12px">
          <iframe id="mainPlayer" src="https://www.youtube.com/embed/evH0I7Coc54" frameborder="0" allowfullscreen></iframe>
        </div>

        <div id="videoList" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px">
          <!-- thumbnails will be inserted here -->
        </div>
      </div>

      <script>
        (function(){
          const videos = [
            {id:'evH0I7Coc54', title:'Stem Cells Intro'},
            {id:'MQXZ7cGZo1w', title:'Stem Cell Types'},
            {id:'3KDsB7Rjaaw', title:'Stem Cell Uses'},
            {id:'Rlbx0ZpJxsU', title:'Therapy Overview'},
            {id:'oy0RPZRLT8g', title:'Clinical Insights'},
            {id:'thXCxztTP60', title:'Research Methods'},
            {id:'lmPqdWnirVY', title:'Safety Guidelines'}
          ];

          const list = document.getElementById('videoList');
          const main = document.getElementById('mainPlayer');

          videos.forEach((v, idx) => {
            const div = document.createElement('div');
            div.style.cursor = 'pointer';
            div.style.border = '1px solid #eee';
            div.style.padding = '6px';
            div.style.borderRadius = '6px';
            div.innerHTML = `<img src="https://img.youtube.com/vi/${v.id}/hqdefault.jpg" style="width:100%;border-radius:4px"><div style="margin-top:6px;font-size:14px">${idx+1}. ${v.title}</div>`;
            div.addEventListener('click', () => {
              main.src = 'https://www.youtube.com/embed/' + v.id + '?rel=0';
              // highlight selected
              Array.from(list.children).forEach(c=>c.style.boxShadow='none');
              div.style.boxShadow = '0 2px 8px rgba(0,0,0,0.12)';
            });
            list.appendChild(div);
          });
          // highlight first thumbnail by default
          if (list.children.length>0) { list.children[0].style.boxShadow='0 2px 8px rgba(0,0,0,0.12)'; }
        })();
      </script>
    </section>

  </main>
</body>
</html>