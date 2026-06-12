matches.forEach(function(m) {
  var tag = addSelfClosingTag(config.tagName, m[0][1], m[0][0].length, -100);

  var isComment = m[3] && m[3][0].startsWith('#');

  var attributes = {
      'repo': m[1][0],
      'pr': m[2][0],
      'comment': isComment ? m[3][0] : '',
      'commit': m[5] && m[5][0] || '',
  };

  tag.setAttributes(attributes);
});
