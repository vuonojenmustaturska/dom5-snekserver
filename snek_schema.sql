--
-- PostgreSQL database dump
--

-- Dumped from database version 11.1
-- Dumped by pg_dump version 11.1

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO dom5snek;

--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.failed_jobs (
    id integer NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT ('now'::text)::timestamp(0) with time zone NOT NULL,
    exception text NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO dom5snek;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO dom5snek;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: game_mod; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.game_mod (
    game_id integer NOT NULL,
    mod_id integer NOT NULL,
    load_order integer
);


ALTER TABLE public.game_mod OWNER TO dom5snek;

--
-- Name: gamepermissions; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.gamepermissions (
    id integer NOT NULL,
    game_id integer NOT NULL,
    user_id integer NOT NULL,
    permission integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.gamepermissions OWNER TO dom5snek;

--
-- Name: gamepermissions_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.gamepermissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.gamepermissions_id_seq OWNER TO dom5snek;

--
-- Name: gamepermissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.gamepermissions_id_seq OWNED BY public.gamepermissions.id;


--
-- Name: games; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.games (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    port integer,
    masterpw character varying(255) NOT NULL,
    era integer NOT NULL,
    hours integer,
    hofsize integer,
    indepstr integer,
    magicsites integer,
    eventrarity integer,
    richness integer,
    resources integer,
    startprov integer,
    victorycond integer NOT NULL,
    requiredap integer,
    lvl1thrones integer,
    lvl2thrones integer,
    lvl3thrones integer,
    totalvp integer,
    requiredvp integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    state integer DEFAULT 0 NOT NULL,
    user_id integer NOT NULL,
    research integer,
    supplies integer,
    renaming boolean DEFAULT true NOT NULL,
    teamgame boolean DEFAULT false NOT NULL,
    noartrest boolean DEFAULT false NOT NULL,
    clustered boolean DEFAULT false NOT NULL,
    scoregraphs boolean DEFAULT false NOT NULL,
    nonationinfo boolean DEFAULT false NOT NULL,
    map_id integer NOT NULL,
    deleted_at timestamp(0) without time zone,
    status integer DEFAULT 0 NOT NULL,
    journal text,
    shortname character varying(255) NOT NULL,
    summervp boolean DEFAULT false NOT NULL,
    capitalvp boolean DEFAULT false NOT NULL,
    cataclysm integer,
    globals integer DEFAULT 5 NOT NULL,
    storyevents integer DEFAULT 0 NOT NULL,
    norandres boolean DEFAULT false NOT NULL
);


ALTER TABLE public.games OWNER TO dom5snek;

--
-- Name: games_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.games_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.games_id_seq OWNER TO dom5snek;

--
-- Name: games_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.games_id_seq OWNED BY public.games.id;


--
-- Name: gamestats; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.gamestats (
    id integer NOT NULL,
    game_id integer NOT NULL,
    turn integer NOT NULL,
    nation_id integer NOT NULL,
    provinces integer,
    forts integer,
    income integer,
    gemincome integer,
    dominion integer,
    armysize integer,
    victorypoints integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    turn_status character varying(255) NOT NULL,
    research integer
);


ALTER TABLE public.gamestats OWNER TO dom5snek;

--
-- Name: gamestats_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.gamestats_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.gamestats_id_seq OWNER TO dom5snek;

--
-- Name: gamestats_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.gamestats_id_seq OWNED BY public.gamestats.id;


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts integer NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO dom5snek;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.jobs_id_seq OWNER TO dom5snek;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: lobbies; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.lobbies (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    maxplayers integer NOT NULL,
    status integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone,
    game_id integer
);


ALTER TABLE public.lobbies OWNER TO dom5snek;

--
-- Name: lobbies_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.lobbies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.lobbies_id_seq OWNER TO dom5snek;

--
-- Name: lobbies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.lobbies_id_seq OWNED BY public.lobbies.id;


--
-- Name: maps; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.maps (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    filename character varying(255) NOT NULL,
    provinces integer NOT NULL,
    imagefile character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id integer NOT NULL
);


ALTER TABLE public.maps OWNER TO dom5snek;

--
-- Name: maps_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.maps_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.maps_id_seq OWNER TO dom5snek;

--
-- Name: maps_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.maps_id_seq OWNED BY public.maps.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.migrations (
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO dom5snek;

--
-- Name: mods; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.mods (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    filename character varying(255) NOT NULL,
    version character varying(255) NOT NULL,
    icon character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    user_id integer NOT NULL,
    disablesoldnations boolean DEFAULT false NOT NULL
);


ALTER TABLE public.mods OWNER TO dom5snek;

--
-- Name: mods_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.mods_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.mods_id_seq OWNER TO dom5snek;

--
-- Name: mods_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.mods_id_seq OWNED BY public.mods.id;


--
-- Name: nationrules; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.nationrules (
    id integer NOT NULL,
    game_id integer NOT NULL,
    nation_id integer NOT NULL,
    type integer NOT NULL,
    team integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.nationrules OWNER TO dom5snek;

--
-- Name: nationrules_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.nationrules_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nationrules_id_seq OWNER TO dom5snek;

--
-- Name: nationrules_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.nationrules_id_seq OWNED BY public.nationrules.id;


--
-- Name: nations; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.nations (
    id integer NOT NULL,
    nation_id integer NOT NULL,
    era integer,
    name character varying(255),
    epithet character varying(255),
    brief text,
    description text,
    implemented_by integer,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    flag character varying(255)
);


ALTER TABLE public.nations OWNER TO dom5snek;

--
-- Name: nations_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.nations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.nations_id_seq OWNER TO dom5snek;

--
-- Name: nations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.nations_id_seq OWNED BY public.nations.id;


--
-- Name: notifications; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.notifications (
    id uuid NOT NULL,
    type character varying(255) NOT NULL,
    notifiable_id integer NOT NULL,
    notifiable_type character varying(255) NOT NULL,
    data text NOT NULL,
    read_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.notifications OWNER TO dom5snek;

--
-- Name: password_resets; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.password_resets OWNER TO dom5snek;

--
-- Name: signups; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.signups (
    id integer NOT NULL,
    user_id integer,
    lobby_id integer NOT NULL,
    nation_id integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    write_in character varying(255),
    password character varying(255)
);


ALTER TABLE public.signups OWNER TO dom5snek;

--
-- Name: signups_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.signups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.signups_id_seq OWNER TO dom5snek;

--
-- Name: signups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.signups_id_seq OWNED BY public.signups.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: dom5snek
--

CREATE TABLE public.users (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    state integer DEFAULT 0 NOT NULL,
    is_admin boolean DEFAULT false NOT NULL,
    is_vouched boolean DEFAULT true NOT NULL,
    deleted_at timestamp(0) without time zone
);


ALTER TABLE public.users OWNER TO dom5snek;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: dom5snek
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO dom5snek;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dom5snek
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: gamepermissions id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.gamepermissions ALTER COLUMN id SET DEFAULT nextval('public.gamepermissions_id_seq'::regclass);


--
-- Name: games id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.games ALTER COLUMN id SET DEFAULT nextval('public.games_id_seq'::regclass);


--
-- Name: gamestats id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.gamestats ALTER COLUMN id SET DEFAULT nextval('public.gamestats_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: lobbies id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.lobbies ALTER COLUMN id SET DEFAULT nextval('public.lobbies_id_seq'::regclass);


--
-- Name: maps id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.maps ALTER COLUMN id SET DEFAULT nextval('public.maps_id_seq'::regclass);


--
-- Name: mods id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.mods ALTER COLUMN id SET DEFAULT nextval('public.mods_id_seq'::regclass);


--
-- Name: nationrules id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.nationrules ALTER COLUMN id SET DEFAULT nextval('public.nationrules_id_seq'::regclass);


--
-- Name: nations id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.nations ALTER COLUMN id SET DEFAULT nextval('public.nations_id_seq'::regclass);


--
-- Name: signups id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.signups ALTER COLUMN id SET DEFAULT nextval('public.signups_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: cache cache_key_unique; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_key_unique UNIQUE (key);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: game_mod game_mod_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.game_mod
    ADD CONSTRAINT game_mod_pkey PRIMARY KEY (game_id, mod_id);


--
-- Name: gamepermissions gamepermissions_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.gamepermissions
    ADD CONSTRAINT gamepermissions_pkey PRIMARY KEY (id);


--
-- Name: games games_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.games
    ADD CONSTRAINT games_pkey PRIMARY KEY (id);


--
-- Name: games games_shortname_unique; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.games
    ADD CONSTRAINT games_shortname_unique UNIQUE (shortname);


--
-- Name: gamestats gamestats_game_id_turn_nation_id_unique; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.gamestats
    ADD CONSTRAINT gamestats_game_id_turn_nation_id_unique UNIQUE (game_id, turn, nation_id);


--
-- Name: gamestats gamestats_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.gamestats
    ADD CONSTRAINT gamestats_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: lobbies lobbies_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.lobbies
    ADD CONSTRAINT lobbies_pkey PRIMARY KEY (id);


--
-- Name: maps maps_filename_unique; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.maps
    ADD CONSTRAINT maps_filename_unique UNIQUE (filename);


--
-- Name: maps maps_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.maps
    ADD CONSTRAINT maps_pkey PRIMARY KEY (id);


--
-- Name: mods mods_filename_unique; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.mods
    ADD CONSTRAINT mods_filename_unique UNIQUE (filename);


--
-- Name: mods mods_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.mods
    ADD CONSTRAINT mods_pkey PRIMARY KEY (id);


--
-- Name: nationrules nationrules_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.nationrules
    ADD CONSTRAINT nationrules_pkey PRIMARY KEY (id);


--
-- Name: nations nations_nation_id_implemented_by_unique; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.nations
    ADD CONSTRAINT nations_nation_id_implemented_by_unique UNIQUE (nation_id, implemented_by);


--
-- Name: nations nations_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.nations
    ADD CONSTRAINT nations_pkey PRIMARY KEY (id);


--
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- Name: signups signups_nation_id_lobby_id_unique; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.signups
    ADD CONSTRAINT signups_nation_id_lobby_id_unique UNIQUE (nation_id, lobby_id);


--
-- Name: signups signups_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.signups
    ADD CONSTRAINT signups_pkey PRIMARY KEY (id);


--
-- Name: signups signups_user_id_lobby_id_unique; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.signups
    ADD CONSTRAINT signups_user_id_lobby_id_unique UNIQUE (user_id, lobby_id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: game_mod_game_id_index; Type: INDEX; Schema: public; Owner: dom5snek
--

CREATE INDEX game_mod_game_id_index ON public.game_mod USING btree (game_id);


--
-- Name: game_mod_mod_id_index; Type: INDEX; Schema: public; Owner: dom5snek
--

CREATE INDEX game_mod_mod_id_index ON public.game_mod USING btree (mod_id);


--
-- Name: jobs_queue_reserved_at_index; Type: INDEX; Schema: public; Owner: dom5snek
--

CREATE INDEX jobs_queue_reserved_at_index ON public.jobs USING btree (queue, reserved_at);


--
-- Name: nations_nation_id_index; Type: INDEX; Schema: public; Owner: dom5snek
--

CREATE INDEX nations_nation_id_index ON public.nations USING btree (nation_id);


--
-- Name: notifications_notifiable_id_notifiable_type_index; Type: INDEX; Schema: public; Owner: dom5snek
--

CREATE INDEX notifications_notifiable_id_notifiable_type_index ON public.notifications USING btree (notifiable_id, notifiable_type);


--
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: dom5snek
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- Name: password_resets_token_index; Type: INDEX; Schema: public; Owner: dom5snek
--

CREATE INDEX password_resets_token_index ON public.password_resets USING btree (token);


--
-- Name: signups_lobby_id_index; Type: INDEX; Schema: public; Owner: dom5snek
--

CREATE INDEX signups_lobby_id_index ON public.signups USING btree (lobby_id);


--
-- Name: signups_nation_id_index; Type: INDEX; Schema: public; Owner: dom5snek
--

CREATE INDEX signups_nation_id_index ON public.signups USING btree (nation_id);


--
-- Name: signups_user_id_index; Type: INDEX; Schema: public; Owner: dom5snek
--

CREATE INDEX signups_user_id_index ON public.signups USING btree (user_id);


--
-- Name: game_mod game_mod_game_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.game_mod
    ADD CONSTRAINT game_mod_game_id_foreign FOREIGN KEY (game_id) REFERENCES public.games(id) ON DELETE CASCADE;


--
-- Name: game_mod game_mod_mod_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.game_mod
    ADD CONSTRAINT game_mod_mod_id_foreign FOREIGN KEY (mod_id) REFERENCES public.mods(id) ON DELETE CASCADE;


--
-- Name: games games_map_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.games
    ADD CONSTRAINT games_map_id_foreign FOREIGN KEY (map_id) REFERENCES public.maps(id);


--
-- Name: games games_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.games
    ADD CONSTRAINT games_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: maps maps_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.maps
    ADD CONSTRAINT maps_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: mods mods_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.mods
    ADD CONSTRAINT mods_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- Name: nations nations_implemented_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.nations
    ADD CONSTRAINT nations_implemented_by_foreign FOREIGN KEY (implemented_by) REFERENCES public.mods(id);


--
-- Name: signups signups_lobby_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.signups
    ADD CONSTRAINT signups_lobby_id_foreign FOREIGN KEY (lobby_id) REFERENCES public.lobbies(id) ON DELETE CASCADE;


--
-- Name: signups signups_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: dom5snek
--

ALTER TABLE ONLY public.signups
    ADD CONSTRAINT signups_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

