USE switch_store_ec;

/* Category */
INSERT INTO Category (name) VALUES
('Consolas'),
('Juegos'),
('Accesorios'),
('Cartas Pokémon');

/* Product */
INSERT INTO Product (name, description, category_id, price, image) VALUES
('Nintendo Switch - Gray', 'Disfruta de la versatilidad de la Nintendo Switch con un diseño elegante en color gris. Ideal para jugar en casa o llevarla contigo a donde vayas.', 1, 299.99, 'imagen_1.webp'),
('Nintendo Switch - Neon Blue & Neon Red', 'Vive la experiencia de la Nintendo Switch con un toque vibrante en los controles Joy-Con de colores azul neón y rojo neón. Perfecta para toda la familia.', 1, 299.99, 'imagen_2.webp'),
('Nintendo Switch OLED - White', 'Descubre la pantalla OLED de alta calidad y disfruta de colores intensos con la Nintendo Switch OLED en color blanco. Eleva tu experiencia de juego.', 1, 349.99, 'imagen_3.webp'),
('Nintendo Switch OLED - Neon Blue & Neon Red', 'La combinación perfecta entre tecnología y estilo. Esta Nintendo Switch OLED cuenta con controles en azul y rojo neón para sesiones de juego inolvidables.', 1, 349.99, 'imagen_4.webp'),
('Nintendo Switch Lite - Gray', 'Diseñada para jugar en cualquier lugar, la Nintendo Switch Lite en color gris es la opción más portátil para tus aventuras de juego.', 1, 199.99, 'imagen_5.webp'),
('Nintendo Switch Lite - Turquoise', 'Un diseño compacto y divertido en color turquesa. Lleva tus juegos favoritos a todas partes con la Nintendo Switch Lite.', 1, 199.99, 'imagen_6.webp'),
('Mario Kart 8 Deluxe', 'Compite con amigos y familiares en las pistas más emocionantes con Mario Kart 8 Deluxe. Diversión garantizada para todos.', 2, 59.99, 'imagen_7.webp'),
('Super Mario Odyssey', 'Embárcate en una épica aventura con Mario y su fiel compañero Cappy para salvar a la princesa Peach. Un clásico moderno.', 2, 59.99, 'imagen_8.webp'),
('Super Mario Bros. Wonder', 'Descubre nuevos mundos y sorpresas mágicas en esta entrega revolucionaria de la serie Super Mario Bros.', 2, 59.99, 'imagen_9.webp'),
('Mario & Luigi Bothership', 'Únete a Mario y Luigi en una hilarante y emocionante aventura que combina acción y estrategia.', 2, 59.99, 'imagen_10.webp'),
('The Legend of Zelda Breath of the Wild', 'Explora el vasto mundo de Hyrule con total libertad en este juego aclamado como uno de los mejores de todos los tiempos.', 2, 59.99, 'imagen_11.webp'),
('The Legend of Zelda Tears of the Kingdom', 'Acompaña a Link en una nueva y épica aventura llena de desafíos y misterios. Una secuela que no te puedes perder.', 2, 69.99, 'imagen_12.webp'),
('The Legend of Zelda Echoes of Wisdom', 'Sumérgete en un emocionante viaje lleno de acertijos, magia y acción con esta nueva entrega donde Zelda es la encargada de salvar Hyrule.', 2, 59.99, 'imagen_13.webp'),
('Pokémon Scarlet', 'Vive la aventura Pokémon en un mundo abierto lleno de posibilidades y desafíos en la región de Paldea.', 2, 59.99, 'imagen_14.webp'),
('Pokémon Violet', 'Vive la aventura Pokémon en un mundo abierto lleno de posibilidades y desafíos en la región de Paldea.', 2, 59.99, 'imagen_15.webp'),
('Pokémon Legends Arceus', 'Explora el pasado de la región de Sinnoh en esta emocionante precuela que redefine el universo Pokémon.', 2, 59.99, 'imagen_16.webp'),
('Joy-con (L/R) - Gray', 'Controles Joy-Con en color gris, ideales para sesiones de juego versátiles y cómodas en tu Nintendo Switch.', 3, 79.99, 'imagen_17.webp'),
('Joy-con (L/R) - Neon Red & Neon Blue', 'Dale un toque de color a tu consola con estos Joy-Con vibrantes en rojo y azul neón.', 3, 79.99, 'imagen_18.webp'),
('Nintendo Switch Pro Controller - Black', 'Este mando ofrece una experiencia de juego ergonómica y precisa, con una batería de larga duración y conectividad inalámbrica Bluetooth. Ideal para jugar durante horas con comodidad,', 3, 69.99, 'imagen_19.webp'),
('MicroSD Samsung EVO Select - 128 GB', 'Amplía el almacenamiento de tu Nintendo Switch con esta tarjeta MicroSD de alta velocidad.', 3, 14.99, 'imagen_20.webp'),
('MicroSD Samsung EVO Select - 216 GB', 'Amplía el almacenamiento de tu Nintendo Switch con esta tarjeta MicroSD de alta velocidad.', 3, 29.99, 'imagen_21.webp'),
('MicroSD Samsung EVO Select - 512 GB', 'Amplía el almacenamiento de tu Nintendo Switch con esta tarjeta MicroSD de alta velocidad.', 3, 49.99, 'imagen_22.webp'),
('Twilight Masquerade - Booster Box', 'Amplía tu colección y mejora tus mazos con esta Booster Box que incluye 36 sobres de cartas de Pokémon.', 4, 149.99, 'imagen_23.webp'),
('Twilight Masquerade - Elite Trainer Box', 'Prepárate para la batalla con este Elite Trainer Box, que incluye todo lo que necesitas para dominar el juego.', 4, 49.99, 'imagen_24.webp'),
('Stellar Crown - Booster Box', 'Amplía tu colección y mejora tus mazos con esta Booster Box que incluye 36 sobres de cartas de Pokémon.', 4, 149.99, 'imagen_25.webp'),
('Stellar Crown - Elite Trainer Box', 'Prepárate para la batalla con este Elite Trainer Box, que incluye todo lo que necesitas para dominar el juego.', 4, 49.99, 'imagen_26.webp'),
('Surging Sparks - Booster Box', 'Amplía tu colección y mejora tus mazos con esta Booster Box que incluye 36 sobres de cartas de Pokémon.', 4, 149.99, 'imagen_27.webp'),
('Surging Sparks - Elite Trainer Box', 'Prepárate para la batalla con este Elite Trainer Box, que incluye todo lo que necesitas para dominar el juego.', 4, 49.99, 'imagen_28.webp');